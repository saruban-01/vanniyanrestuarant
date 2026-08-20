<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMetadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'route_name',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'robots',
        'schema_type',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}
