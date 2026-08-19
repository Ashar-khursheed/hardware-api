<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryFilterSku extends Model
{
    protected $fillable = [
        'category_filter_value_id',
        'sku',
        'product_id',
    ];

    public function filterValue(): BelongsTo
    {
        return $this->belongsTo(CategoryFilterValue::class, 'category_filter_value_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
