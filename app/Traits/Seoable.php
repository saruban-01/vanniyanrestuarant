<?php

namespace App\Traits;

use App\Models\SeoMetadata;

trait Seoable
{
    /**
     * Get the SEO metadata associated with the model.
     */
    public function seoMetadata()
    {
        return $this->morphOne(SeoMetadata::class, 'model');
    }

    /**
     * Helper to get or create SEO metadata.
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata()->firstOrCreate([]);
    }
}
