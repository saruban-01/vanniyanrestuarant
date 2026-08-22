<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::saved(function ($category) {
            \Illuminate\Support\Facades\Cache::forget('menu_categories_active');
            \Illuminate\Support\Facades\Artisan::call('cache:clear'); // Clear all due to dynamic keys
        });

        static::deleted(function ($category) {
            \Illuminate\Support\Facades\Cache::forget('menu_categories_active');
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
        });
    }

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }
}
