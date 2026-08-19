<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CategoryFilterGroup;
use App\Models\CategoryFilterValue;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportCategoryFilters extends Command
{
    /**
     * Import category filter Excel files.
     *
     * Example:
     * php artisan filters:import cables-adapters "C:\path\to\Cables & Adapters Page"
     *
     * Each Excel file's second column header becomes the filter group name
     * (e.g. Connector, Length). Part#/SKU is matched against products.sku.
     */
    protected $signature = 'filters:import
                            {category_slug : Category slug (e.g. cables-adapters)}
                            {path : Path to a folder of Excel files or a single .xlsx file}
                            {--replace : Remove existing filter groups for this category before import}';

    protected $description = 'Import category-specific product filters from Excel (Part# → filter value)';

    public function handle(): int
    {
        $slug = $this->argument('category_slug');
        $path = $this->argument('path');

        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            $this->error("Category not found for slug: {$slug}");
            return self::FAILURE;
        }

        $files = $this->resolveExcelFiles($path);
        if (empty($files)) {
            $this->error("No Excel files found at: {$path}");
            return self::FAILURE;
        }

        if ($this->option('replace')) {
            $this->warn("Replacing existing filter groups for category [{$category->name}]...");
            CategoryFilterGroup::where('category_id', $category->id)->delete();
        }

        $this->info("Importing filters for [{$category->name}] ({$slug})");
        $this->info('Files: ' . count($files));

        $summary = [
            'groups' => 0,
            'values' => 0,
            'linked' => 0,
            'missing_sku' => 0,
        ];

        foreach ($files as $file) {
            $result = $this->importFile($category, $file);
            $summary['groups'] += $result['groups'];
            $summary['values'] += $result['values'];
            $summary['linked'] += $result['linked'];
            $summary['missing_sku'] += $result['missing_sku'];
        }

        $this->newLine();
        $this->info('Import complete.');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Filter groups upserted', $summary['groups']],
                ['Filter values upserted', $summary['values']],
                ['Product links created/updated', $summary['linked']],
                ['SKUs not found in products', $summary['missing_sku']],
            ]
        );

        return self::SUCCESS;
    }

    protected function resolveExcelFiles(string $path): array
    {
        if (is_file($path) && preg_match('/\.xlsx?$/i', $path)) {
            return [$path];
        }

        if (!is_dir($path)) {
            return [];
        }

        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }
            $name = $file->getFilename();
            // Skip Excel lock/temp files (~$...)
            if (str_starts_with($name, '~$')) {
                continue;
            }
            if (preg_match('/\.xlsx?$/i', $name)) {
                $files[] = $file->getPathname();
            }
        }

        sort($files);
        return $files;
    }

    protected function importFile(Category $category, string $filePath): array
    {
        $this->line("→ " . basename($filePath));

        $spreadsheet = IOFactory::load($filePath);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

        if (count($rows) < 2) {
            $this->warn('  Skipped (empty file)');
            return ['groups' => 0, 'values' => 0, 'linked' => 0, 'missing_sku' => 0];
        }

        $header = array_map(fn ($h) => trim((string) $h), $rows[0]);
        $groupName = $header[1] ?? null;

        if (!$groupName) {
            // Fallback: use filename without extension
            $groupName = pathinfo($filePath, PATHINFO_FILENAME);
            $groupName = preg_replace('/[_-]+/', ' ', $groupName);
            $groupName = Str::title(trim($groupName));
        }

        $groupSlug = Str::slug($groupName);

        $group = CategoryFilterGroup::updateOrCreate(
            [
                'category_id' => $category->id,
                'slug' => $groupSlug,
            ],
            [
                'name' => $groupName,
                'status' => true,
                'sort_order' => CategoryFilterGroup::where('category_id', $category->id)->count(),
            ]
        );

        $valueCache = [];
        $valuesCreated = 0;
        $linked = 0;
        $missing = 0;
        $sort = 0;

        // Prefetch SKUs and names for matching
        $products = Product::query()
            ->whereNull('deleted_at')
            ->get(['id', 'sku', 'name']);

        $skuMap = [];
        $nameMap = [];
        foreach ($products as $product) {
            $skuKey = strtoupper(trim((string) $product->sku));
            if ($skuKey !== '') {
                $skuMap[$skuKey] = (int) $product->id;
            }
            $nameKey = strtoupper(trim((string) $product->name));
            if ($nameKey !== '') {
                $nameMap[$nameKey] = (int) $product->id;
            }
        }

        DB::beginTransaction();
        try {
            for ($i = 1; $i < count($rows); $i++) {
                $part = trim((string) ($rows[$i][0] ?? ''));
                $rawValue = trim((string) ($rows[$i][1] ?? ''));

                if ($part === '' || $rawValue === '') {
                    continue;
                }

                // Preserve "+" (e.g. AM3+ vs AM3, FM2+ vs FM2) so values don't collide
                $normalizedValue = str_replace(
                    ['+', '.', '/', '\\'],
                    ['-plus', '-', '-', '-'],
                    $rawValue
                );
                $valueSlug = Str::slug($normalizedValue);
                if ($valueSlug === '') {
                    $valueSlug = 'v-' . substr(md5($rawValue), 0, 8);
                }

                if (!isset($valueCache[$valueSlug])) {
                    $filterValue = CategoryFilterValue::updateOrCreate(
                        [
                            'category_filter_group_id' => $group->id,
                            'slug' => $valueSlug,
                        ],
                        [
                            'value' => $rawValue,
                            'sort_order' => $sort++,
                        ]
                    );
                    $valueCache[$valueSlug] = $filterValue->id;
                    $valuesCreated++;
                }

                $productId = $this->resolveProductId($part, $skuMap, $nameMap);

                // Always store Part# → filter value (works even if product not in local DB yet)
                DB::table('category_filter_skus')->updateOrInsert(
                    [
                        'category_filter_value_id' => $valueCache[$valueSlug],
                        'sku' => $part,
                    ],
                    [
                        'product_id' => $productId,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                if (!$productId) {
                    $missing++;
                    $linked++; // SKU mapped even without product row
                    continue;
                }

                DB::table('product_category_filter_values')->updateOrInsert(
                    [
                        'product_id' => $productId,
                        'category_filter_value_id' => $valueCache[$valueSlug],
                    ],
                    [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
                $linked++;
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        $this->info("  Group: {$groupName} | values: {$valuesCreated} | linked: {$linked} | missing SKU: {$missing}");

        return [
            'groups' => 1,
            'values' => $valuesCreated,
            'linked' => $linked,
            'missing_sku' => $missing,
        ];
    }

    protected function resolveProductId(string $part, array $skuMap, array $nameMap = []): ?int
    {
        $candidates = array_unique(array_filter([
            strtoupper($part),
            strtoupper(rtrim($part, '=')),
            strtoupper(trim($part, " \t\n\r\0\x0B=\"'")),
        ]));

        foreach ($candidates as $sku) {
            if (isset($skuMap[$sku])) {
                return (int) $skuMap[$sku];
            }
        }

        // Fallback: product name starts with Part# (common for hardware catalogs)
        foreach ($candidates as $sku) {
            if (isset($nameMap[$sku])) {
                return (int) $nameMap[$sku];
            }
            foreach ($nameMap as $name => $id) {
                if (str_starts_with($name, $sku . ' ') || str_starts_with($name, $sku . '-') || $name === $sku) {
                    return (int) $id;
                }
            }
        }

        return null;
    }
}
