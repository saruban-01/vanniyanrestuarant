<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Seoable;

    protected $fillable = [
        'slug',
        'title',
        'category',
        'excerpt',
        'content',
        'blocks',
        'sources',
        'reading_time_minutes',
        'image',
        'is_published',
        'is_featured',
        'order',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'blocks' => 'array',
        'sources' => 'array',
        'reading_time_minutes' => 'integer',
    ];

    /**
     * Scope: Only published stories.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Only featured stories.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
