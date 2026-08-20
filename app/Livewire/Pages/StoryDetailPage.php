<?php

namespace App\Livewire\Pages;

use App\Models\Story;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class StoryDetailPage extends Component
{
    public Story $story;
    public $relatedStories;
    public bool $isFromQR = false;

    public function mount($slug)
    {
        $this->story = Story::published()->where('slug', $slug)->firstOrFail();
        
        $this->isFromQR = Request::query('source') === 'qr';

        $this->relatedStories = Story::published()
            ->where('id', '!=', $this->story->id)
            ->where('category', $this->story->category)
            ->limit(3)
            ->get();

        if ($this->relatedStories->isEmpty()) {
            $this->relatedStories = Story::published()
                ->where('id', '!=', $this->story->id)
                ->limit(3)
                ->get();
        }

        $this->dispatch('vanniyan-track', [
            'event' => 'story_viewed',
            'data' => [
                'story_id' => $this->story->id,
                'story_title' => $this->story->title,
                'story_slug' => $this->story->slug,
                'story_source' => $this->isFromQR ? 'qr' : 'web',
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.pages.story-detail-page')->layout('components.layouts.app', [
            'title' => $this->story->title . ' | Vanniyan Stories',
            'meta_description' => $this->story->excerpt,
            'og_image' => $this->story->image,
        ]);
    }
}
