<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CategoryFilterValue extends Model
{
    protected $fillable = [
        'category_filter_group_id',
        'value',
        'slug',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function filterGroup(): BelongsTo
    {
        return $this->belongsTo(CategoryFilterGroup::class, 'category_filter_group_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'product_category_filter_values',
            'category_filter_value_id',
            'product_id'
        )->withTimestamps();
    }
}
