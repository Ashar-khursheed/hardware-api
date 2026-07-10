<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AllCategoriesContentSeeder extends Seeder
{
    /**
     * Run: php artisan db:seed --class=AllCategoriesContentSeeder
     */
    public function run()
    {
        $jsonPath = database_path('seeders/data/category-content.json');

        if (!File::exists($jsonPath)) {
            $this->command?->error("Missing data file: {$jsonPath}");
            return;
        }

        $items = json_decode(File::get($jsonPath), true);

        if (!is_array($items)) {
            $this->command?->error('Invalid category-content.json format.');
            return;
        }

        $updated = 0;
        $skipped = 0;
        $missing = [];

        foreach ($items as $item) {
            $slug = $item['slug'] ?? null;
            if (!$slug) {
                $skipped++;
                continue;
            }

            $category = Category::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->first();

            if (!$category) {
                $missing[] = $slug;
                continue;
            }

            if (!empty($item['heading'])) {
                $category->setTranslation('heading', 'en', $item['heading']);
            }
            if (!empty($item['description'])) {
                $category->setTranslation('description', 'en', $item['description']);
            }
            if (!empty($item['content'])) {
                $category->setTranslation('content', 'en', $item['content']);
            }

            // saveQuietly() prevents the Sluggable trait from regenerating the
            // slug from the name, which would otherwise change/break live URLs.
            $category->saveQuietly();
            $updated++;
            $this->command?->info("Updated: {$slug} (id: {$category->id})");
        }

        $this->command?->info("Done. Updated {$updated} categories, skipped {$skipped}.");

        if ($missing) {
            $this->command?->warn('Categories not found in DB (' . count($missing) . '):');
            foreach ($missing as $slug) {
                $this->command?->warn(" - {$slug}");
            }
        }
    }
}
