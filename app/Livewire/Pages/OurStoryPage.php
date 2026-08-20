<?php

namespace App\Livewire\Pages;

use App\Models\Story;
use Livewire\Component;

class OurStoryPage extends Component
{
    public function render()
    {
        $featuredStory = Story::published()->featured()->orderBy('order')->first();
        
        $storiesQuery = Story::published();
        if ($featuredStory) {
            $storiesQuery->where('id', '!=', $featuredStory->id);
        }
        $stories = $storiesQuery->orderBy('order')->get();

        return view('livewire.pages.our-story-page', [
            'featuredStory' => $featuredStory,
            'stories' => $stories,
        ])->layout('components.layouts.app', [
            'title' => 'Our Story | Vanniyan Restaurant | The Royal Taste of Vanni',
            'meta_description' => 'Discover the story behind Vanniyan Restaurant and explore the food, people, place and cultural inspiration that shape the Vanniyan experience in Kilinochchi.',
        ]);
    }
}
