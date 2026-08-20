<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CategoryFilterSeeder extends Seeder
{
    public function run(): void
    {
        $files = [
            'category_filter_groups.sql',
            'category_filter_values.sql',
            'category_filter_skus.sql',
        ];

        foreach ($files as $file) {
            $path = database_path("seeders/data/{$file}");

            if (!File::exists($path)) {
                throw new \RuntimeException("SQL file not found: {$file}");
            }

            $this->command->info("Importing {$file}...");

            $sql = File::get($path);

            // Only execute INSERT statements.
            preg_match_all(
                '/INSERT\s+INTO\s+`?[^;]+;/is',
                $sql,
                $matches
            );

            foreach ($matches[0] as $insert) {
                DB::unprepared($insert);
            }

            $this->command->info(
                "Imported " . count($matches[0]) . " INSERT statement(s) from {$file}"
            );
        }
    }
}