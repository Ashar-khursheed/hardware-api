<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryFilterGroup;
use App\Models\CategoryFilterSku;
use Exception;
use Illuminate\Http\Request;
use App\GraphQL\Exceptions\ExceptionHandler;

class CategoryFilterController extends Controller
{
    /**
     * Return dynamic filter groups for a category (only that category's filters).
     * GET /category-filters?category_slug=cables-adapters
     */
    public function index(Request $request)
    {
        try {
            $slug = $request->category_slug ?? $request->slug;
            $categoryId = $request->category_id;

            if (!$slug && !$categoryId) {
                return response()->json(['data' => []]);
            }

            $category = null;
            if ($categoryId) {
                $category = Category::find($categoryId);
            } elseif ($slug) {
                $category = Category::where('slug', $slug)->first();
            }

            if (!$category) {
                return response()->json(['data' => []]);
            }

            $groups = CategoryFilterGroup::query()
                ->where('category_id', $category->id)
                ->where('status', true)
                ->with(['values' => function ($q) {
                    $q->orderBy('sort_order')->orderBy('value');
                }])
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function (CategoryFilterGroup $group) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                        'slug' => $group->slug,
                        'sort_order' => $group->sort_order,
                        'attribute_values' => $group->values->map(function ($value) {
                            return [
                                'id' => $value->id,
                                'value' => $value->value,
                                'slug' => $value->slug,
                                'sort_order' => $value->sort_order,
                            ];
                        })->values(),
                    ];
                })
                ->values();

            return response()->json(['data' => $groups]);
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Return product SKUs matching selected category filters (AND across groups).
     * GET /category-filters/skus?category_slug=cables-adapters&category_filters=connector:usb-c;length:1m
     */
    public function matchingSkus(Request $request)
    {
        try {
            $slug = $request->category_slug ?? $request->slug;
            $category = $slug ? Category::where('slug', $slug)->first() : null;

            if (!$category || !$request->filled('category_filters')) {
                return response()->json(['data' => []]);
            }

            $skus = self::resolveMatchingSkus($category->id, $request->category_filters);

            return response()->json(['data' => array_values($skus)]);
        } catch (Exception $e) {
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return string[] normalized uppercase SKUs
     */
    public static function resolveMatchingSkus(int $categoryId, string $categoryFilters): array
    {
            $groups = array_filter(preg_split('/[|;]/', $categoryFilters));
            $matching = null;

            foreach ($groups as $group) {
            if (!str_contains($group, ':')) {
                continue;
            }
            [$groupSlug, $valuesStr] = explode(':', $group, 2);
            $valueSlugs = array_values(array_filter(array_map('trim', explode(',', $valuesStr))));
            if ($groupSlug === '' || empty($valueSlugs)) {
                continue;
            }

            $skus = CategoryFilterSku::query()
                ->whereHas('filterValue', function ($q) use ($categoryId, $groupSlug, $valueSlugs) {
                    $q->whereIn('slug', $valueSlugs)
                        ->whereHas('filterGroup', function ($gq) use ($categoryId, $groupSlug) {
                            $gq->where('category_id', $categoryId)
                                ->where('slug', $groupSlug)
                                ->where('status', true);
                        });
                })
                ->pluck('sku')
                ->map(fn ($sku) => strtoupper(rtrim(trim((string) $sku), '=')))
                ->unique()
                ->values()
                ->all();

            $matching = $matching === null ? $skus : array_values(array_intersect($matching, $skus));
        }

        return $matching ?? [];
    }
}
