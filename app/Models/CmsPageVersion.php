<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPageVersion extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'seo_meta' => 'array',
    ];

    public function page()
    {
        return $this->belongsTo(CmsPage::class, 'cms_page_id');
    }

    public function creator()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin_id');
    }
}
