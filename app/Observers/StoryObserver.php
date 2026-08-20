<?php

namespace App\Observers;

use App\Models\Story;
use App\Models\SlugRedirect;

class StoryObserver
{
    /**
     * Handle the Story "updated" event.
     */
    public function updated(Story $story): void
    {
        if ($story->isDirty('slug')) {
            $oldSlug = $story->getOriginal('slug');
            $newSlug = $story->slug;

            if ($oldSlug && $newSlug && $oldSlug !== $newSlug) {
                SlugRedirect::create([
                    'old_path' => '/our-stories/' . $oldSlug,
                    'new_path' => '/our-stories/' . $newSlug,
                    'status_code' => 301,
                ]);
            }
        }
    }
}
