<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Helper function to normalize slug (preserving +)
        $getCleanSlug = function($value) {
            $normalized = str_replace(
                ['+', '.', '/', '\\'],
                ['-plus', '-', '-', '-'],
                $value
            );
            $slug = Str::slug($normalized);
            if ($slug === '') {
                $slug = 'v-' . substr(md5($value), 0, 8);
            }
            return $slug;
        };

        $values = DB::table('category_filter_values')->get();

        foreach ($values as $valRow) {
            $rawValue = trim($valRow->value);
            $groupId = $valRow->category_filter_group_id;

            // Fetch group details to know category_id and slug
            $group = DB::table('category_filter_groups')->where('id', $groupId)->first();
            if (!$group) continue;

            $category = DB::table('categories')->where('id', $group->category_id)->first();
            $categorySlug = $category ? $category->slug : '';
            $groupSlug = $group->slug;

            // 1. Skip / Delete spreadsheet errors entirely
            if (strcasecmp($rawValue, '#VALUE!') === 0 || strcasecmp($rawValue, '#N/A') === 0 || strcasecmp($rawValue, 'N/A') === 0 || $rawValue === '') {
                DB::table('product_category_filter_values')->where('category_filter_value_id', $valRow->id)->delete();
                DB::table('category_filter_skus')->where('category_filter_value_id', $valRow->id)->delete();
                DB::table('category_filter_values')->where('id', $valRow->id)->delete();
                continue;
            }

            $cleanedValue = $rawValue;

            // 2. Memories / Speed cleanup
            if ($categorySlug === 'memories' && $groupSlug === 'speed') {
                $cleanedValue = preg_replace('/\s*mhz\b/i', 'MHz', $cleanedValue);
                $cleanedValue = preg_replace('/\s*\(?N\/A\)?/i', '', $cleanedValue);
                $cleanedValue = trim($cleanedValue);
            }

            // 3. Storage devices / Cache cleanup
            if ($categorySlug === 'storage-devices' && $groupSlug === 'cache') {
                $cleanedValue = preg_replace('/\s*mb\b/i', 'MB', $cleanedValue);
                $cleanedValue = trim($cleanedValue);
            }

            // 4. Motherboards / Chipset cleanup
            if ($categorySlug === 'motherboards' && $groupSlug === 'chipset') {
                if (preg_match('/^N\/A\s+\(?(.*?)\)?$/i', $cleanedValue, $matches)) {
                    $cleanedValue = trim($matches[1]);
                } else {
                    $cleanedValue = preg_replace('/^N\/A\s+/i', '', $cleanedValue);
                    $cleanedValue = preg_replace('/\s*\(?N\/A\)?/i', '', $cleanedValue);
                    $cleanedValue = trim($cleanedValue);
                }
            }

            // Check if value is now empty or invalid
            if ($cleanedValue === '' || strcasecmp($cleanedValue, '#VALUE!') === 0) {
                DB::table('product_category_filter_values')->where('category_filter_value_id', $valRow->id)->delete();
                DB::table('category_filter_skus')->where('category_filter_value_id', $valRow->id)->delete();
                DB::table('category_filter_values')->where('id', $valRow->id)->delete();
                continue;
            }

            if ($cleanedValue !== $rawValue) {
                $newSlug = $getCleanSlug($cleanedValue);

                // Check duplicate
                $existing = DB::table('category_filter_values')
                    ->where('category_filter_group_id', $groupId)
                    ->where('slug', $newSlug)
                    ->first();

                if ($existing) {
                    if ($existing->id !== $valRow->id) {
                        // Re-route SKUs mappings to the existing value without conflicts
                        $existingSkus = DB::table('category_filter_skus')
                            ->where('category_filter_value_id', $existing->id)
                            ->pluck('sku')
                            ->toArray();

                        if (!empty($existingSkus)) {
                            DB::table('category_filter_skus')
                                ->where('category_filter_value_id', $valRow->id)
                                ->whereIn('sku', $existingSkus)
                                ->delete();
                        }

                        DB::table('category_filter_skus')
                            ->where('category_filter_value_id', $valRow->id)
                            ->update(['category_filter_value_id' => $existing->id]);

                        // Re-route Product mappings to the existing value without conflicts
                        $existingProductIds = DB::table('product_category_filter_values')
                            ->where('category_filter_value_id', $existing->id)
                            ->pluck('product_id')
                            ->toArray();

                        if (!empty($existingProductIds)) {
                            DB::table('product_category_filter_values')
                                ->where('category_filter_value_id', $valRow->id)
                                ->whereIn('product_id', $existingProductIds)
                                ->delete();
                        }

                        DB::table('product_category_filter_values')
                            ->where('category_filter_value_id', $valRow->id)
                            ->update(['category_filter_value_id' => $existing->id]);

                        // Delete duplicate value row
                        DB::table('category_filter_values')->where('id', $valRow->id)->delete();
                    }
                } else {
                    // Update in place
                    DB::table('category_filter_values')
                        ->where('id', $valRow->id)
                        ->update([
                            'value' => $cleanedValue,
                            'slug' => $newSlug,
                            'updated_at' => now(),
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rolling back data normalization modifications
    }
};
