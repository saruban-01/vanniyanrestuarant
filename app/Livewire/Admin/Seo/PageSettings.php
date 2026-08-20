<?php

namespace App\Livewire\Admin\Seo;

use App\Models\SeoMetadata;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class PageSettings extends Component
{
    public $staticPages = [
        'home' => 'Homepage',
        'menu' => 'Menu',
        'offers' => 'Our Deals',
        'stories.index' => 'Our Story',
        'reservation' => 'Table Reservation',
        'contact' => 'Contact & Location',
    ];

    public $editingRoute = null;
    
    // Form fields
    public $meta_title;
    public $meta_description;
    public $canonical_url;
    public $og_title;
    public $og_description;
    public $og_image;
    public $robots = 'index, follow';
    public $schema_type;

    public function editPage($route)
    {
        $this->editingRoute = $route;
        $metadata = SeoMetadata::where('route_name', $route)->first();
        
        if ($metadata) {
            $this->meta_title = $metadata->meta_title;
            $this->meta_description = $metadata->meta_description;
            $this->canonical_url = $metadata->canonical_url;
            $this->og_title = $metadata->og_title;
            $this->og_description = $metadata->og_description;
            $this->og_image = $metadata->og_image;
            $this->robots = $metadata->robots;
            $this->schema_type = $metadata->schema_type;
        } else {
            $this->resetFields();
            if ($route === 'home' || $route === 'contact') {
                $this->schema_type = 'Restaurant';
            }
        }
    }

    public function cancelEdit()
    {
        $this->editingRoute = null;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->meta_title = '';
        $this->meta_description = '';
        $this->canonical_url = '';
        $this->og_title = '';
        $this->og_description = '';
        $this->og_image = '';
        $this->robots = 'index, follow';
        $this->schema_type = '';
    }

    public function save()
    {
        $this->validate([
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:255',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:255',
            'robots' => 'required|string|max:100',
            'schema_type' => 'nullable|string|max:100',
        ]);

        SeoMetadata::updateOrCreate(
            ['route_name' => $this->editingRoute],
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

        \App\Models\AuditLog::log(
            \Illuminate\Support\Facades\Auth::guard('admin')->user(),
            'seo_page_updated',
            "SEO metadata updated for route: {$this->editingRoute}"
        );

        session()->flash('success', 'Page SEO updated successfully.');
        $this->editingRoute = null;
    }

    public function render()
    {
        $metadata = SeoMetadata::whereNotNull('route_name')->get()->keyBy('route_name');
        
        return view('livewire.admin.seo.page-settings', [
            'metadata' => $metadata
        ])->title('Page SEO - Vanniyan Admin');
    }
}
