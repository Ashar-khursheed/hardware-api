<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoriesImportSeeder extends Seeder
{
    /**
     * Import categories from categories.json (live export) for local testing.
     * Preserves EXACT ids and slugs (bypasses the Sluggable trait so slugs
     * are never regenerated from the name).
     *
     * Run: php artisan db:seed --class=CategoriesImportSeeder
     */
    public function run(): void
    {
        $jsonPath = base_path('categories.json');

        if (!File::exists($jsonPath)) {
            $this->command?->error("Missing file: {$jsonPath}");
            return;
        }

        $items = json_decode(File::get($jsonPath), true);

        if (!is_array($items)) {
            $this->command?->error('Invalid categories.json format.');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('product_categories')->truncate();
        DB::table('categories')->truncate();

        $now = now();
        $count = 0;

        foreach ($items as $item) {
            $slug = $item['slug'] ?? null;
            $id = $item['id'] ?? null;
            if (!$slug || !$id) {
                continue;
            }

            DB::table('categories')->insert([
                'id' => $id,
                'name' => json_encode(['en' => $item['name'] ?? $slug]),
                'slug' => $slug,
                'description' => isset($item['description'])
                    ? json_encode(['en' => $item['description']])
                    : null,
                'meta_title' => !empty($item['meta_title'])
                    ? json_encode(['en' => $item['meta_title']])
                    : null,
                'meta_description' => !empty($item['meta_description'])
                    ? json_encode(['en' => $item['meta_description']])
                    : null,
                'status' => $item['status'] ?? 1,
                'type' => $item['type'] ?? 'product',
                'parent_id' => $item['parent_id'] ?? null,
                'created_by_id' => $item['created_by_id'] ?? 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $count++;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command?->info("Imported {$count} categories with exact slugs and ids.");
    }
}
