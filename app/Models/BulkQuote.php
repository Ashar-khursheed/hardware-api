<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BulkQuote extends Model
{
    protected $fillable = [
        'full_name',
        'org_name',
        'email',
        'phone',
        'part_number',
        'quantity',
        'urgency',
        'description',
        'status',
    ];
}
