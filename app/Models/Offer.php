<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Seoable;

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
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'is_dine_in' => 'boolean',
        'is_takeaway' => 'boolean',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    /**
     * Scope a query to only include active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_published', true)
                     ->where(function ($q) {
                         $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
                     });
    }

    /**
     * Scope a query to only include scheduled offers (published but future valid_from).
     */
    public function scopeScheduled($query)
    {
        return $query->where('is_published', true)
                     ->whereNotNull('valid_from')
                     ->where('valid_from', '>', now());
    }

    /**
     * Scope a query to only include expired offers.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('valid_until')
                     ->where('valid_until', '<', now());
    }
}
