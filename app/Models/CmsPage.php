<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function versions()
    {
        return $this->hasMany(CmsPageVersion::class)->orderByDesc('version_number');
    }

    public function publishedVersion()
    {
        return $this->hasOne(CmsPageVersion::class)->where('status', 'PUBLISHED');
    }

    public function draftVersion()
    {
        return $this->hasOne(CmsPageVersion::class)->where('status', 'DRAFT');
    }
}
