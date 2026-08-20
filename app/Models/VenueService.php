<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueService extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price_type',
        'base_price',
        'is_available',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_available' => 'boolean',
        'sort_order' => 'integer',
    ];
}
