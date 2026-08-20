<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::saved(function ($model) {
            \Illuminate\Support\Facades\Cache::flush();
        });

        static::deleted(function ($model) {
            \Illuminate\Support\Facades\Cache::flush();
        });
    }

    protected $casts = [
        'how_it_works' => 'array',
        'terms' => 'array',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the single active loyalty config.
     */
    public static function getActive()
    {
        return self::first(); // Assumes only one row exists
    }
}
