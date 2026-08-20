<?php

namespace App\Livewire\Admin\Stories;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Story;
use Illuminate\Support\Str;

#[Layout('components.layouts.admin')]
class StoryEditor extends Component
{
    public ?Story $story = null;

    public $title = '';
    public $slug = '';
    public $category = '';
    public $excerpt = '';
    public $content = '';
    public $reading_time_minutes = 5;
    public $image = '';
    public $is_published = false;
    public $is_featured = false;
    public $order = 0;

    // SEO
    public $meta_title = '';
    public $meta_description = '';
    public $canonical_url = '';
    public $og_title = '';
    public $og_description = '';
    public $og_image = '';
    public $robots = 'index, follow';
    public $schema_type = '';

    // JSON fields
    public $blocks_json = '';
    public $sources_json = '';

    public function mount(?Story $story = null)
    {
        if ($story && $story->exists) {
            $this->story = $story;
            $this->title = $story->title;
            $this->slug = $story->slug;
            $this->category = $story->category;
            $this->excerpt = $story->excerpt;
            $this->content = $story->content;
            $this->reading_time_minutes = $story->reading_time_minutes;
            $this->image = $story->image;
            $this->is_published = $story->is_published;
            $this->is_featured = $story->is_featured;
            $this->order = $story->order;
            
            $this->blocks_json = $story->blocks ? json_encode($story->blocks, JSON_PRETTY_PRINT) : '';
            $this->sources_json = $story->sources ? json_encode($story->sources, JSON_PRETTY_PRINT) : '';
            
            if ($seo = $story->seoMetadata) {
                $this->meta_title = $seo->meta_title;
                $this->meta_description = $seo->meta_description;
                $this->canonical_url = $seo->canonical_url;
                $this->og_title = $seo->og_title;
                $this->og_description = $seo->og_description;
                $this->og_image = $seo->og_image;
                $this->robots = $seo->robots;
                $this->schema_type = $seo->schema_type;
            }
        } else {
            $this->order = Story::max('order') + 10;
        }
    }

    public function updatedTitle()
    {
        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:stories,slug,' . ($this->story->id ?? 'NULL'),
            'category' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'reading_time_minutes' => 'required|integer|min:1',
            'image' => 'nullable|string',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'required|integer',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'reading_time_minutes' => $this->reading_time_minutes,
            'image' => $this->image,
            'is_published' => $this->is_published,
            'is_featured' => $this->is_featured,
            'order' => $this->order,
            'blocks' => $this->blocks_json ? json_decode($this->blocks_json, true) : null,
            'sources' => $this->sources_json ? json_decode($this->sources_json, true) : null,
        ];

        if (!$this->story) {
            $story = Story::create($data);
        } else {
            $this->story->update($data);
            $story = $this->story;
        }

        $story->seoMetadata()->updateOrCreate(
            [],
            [
                'meta_title' => $this->meta_title,
                'meta_description' => $this->meta_description,
                'canonical_url' => $this->canonical_url,
                'og_title' => $this->og_title,
                'og_description' => $this->og_description,
                'og_image' => $this->og_image,
                'robots' => $this->robots,
                'schema_type' => $this->schema_type,
            ]
        );

        session()->flash('message', 'Story successfully saved.');

        return redirect()->route('admin.stories');
    }

    public function render()
    {
        return view('livewire.admin.stories.story-editor')
            ->title($this->story ? 'Edit Story - Vanniyan CMS' : 'New Story - Vanniyan CMS');
    }
}
