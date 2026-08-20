<?php

namespace App\Livewire\Admin\Stories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Story;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public function togglePublish($id)
    {
        $story = Story::findOrFail($id);
        $story->is_published = !$story->is_published;
        $story->save();
        session()->flash('message', 'Story status updated.');
    }

    public function toggleFeatured($id)
    {
        $story = Story::findOrFail($id);
        $story->is_featured = !$story->is_featured;
        $story->save();
        session()->flash('message', 'Story featured status updated.');
    }

    public function deleteStory($id)
    {
        Story::findOrFail($id)->delete();
        session()->flash('message', 'Story deleted.');
    }

    public function render()
    {
        return view('livewire.admin.stories.index', [
            'stories' => Story::orderBy('order')->orderByDesc('created_at')->get(),
        ])->title('Stories Management - Vanniyan CMS');
    }
}
