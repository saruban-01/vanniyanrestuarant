<?php

namespace App\Livewire\Pages;

use App\Models\Story;
use Livewire\Component;

class SitemapPage extends Component
{
    public function render()
    {
        $stories = Story::published()
            ->orderByDesc('updated_at')
            ->get()
            ->filter(fn (Story $story) => $story->seoMetadata === null || ! str_contains($story->seoMetadata->robots, 'noindex'))
            ->map(fn (Story $story) => [
                'title' => $story->title,
                'url' => route('our-stories.show', $story->slug),
            ]);

        return view('livewire.pages.sitemap', [
            'stories' => $stories,
        ])->layout('components.layouts.app');
    }
}
