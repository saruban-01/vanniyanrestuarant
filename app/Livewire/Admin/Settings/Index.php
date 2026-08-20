<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\CmsService;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public array $content = [
        // Contact Information
        'contact_phone' => '+44 20 1234 5678',
        'contact_email' => 'hello@vanniyan.com',
        'contact_address' => '123 High Street, London, UK',
        
        // Social Media
        'social_instagram' => 'https://instagram.com/vanniyan',
        'social_facebook' => 'https://facebook.com/vanniyan',
        'social_whatsapp' => '',
        'social_tiktok' => '',
        
        // Footer Content
        'footer_text' => 'Authentic Vanni heritage cuisine in the heart of London.',
        'footer_copyright' => ' Vanniyan. All rights reserved.',
        
        // Navigation (Custom links could go here, but sticking to basics)
        'nav_cta_text' => 'Reserve',
        'nav_cta_url' => '/reservation',

        // Google Reviews
        'google_reviews_enabled' => '0',
        'google_reviews_heading' => 'Loved by our guests',
        'google_reviews_subtitle' => 'Real experiences from people who have visited Vanniyan Restaurant.',
        'google_reviews_place_id' => '',
        'google_reviews_url' => '',
        'google_reviews_write_url' => '',
        'google_reviews_count' => '3',
        'google_reviews_cache_minutes' => '1440',
    ];

    public ?array $seoMeta = [
        'title' => 'Vanniyan - Global Settings',
        'description' => 'Default SEO description for the site.',
    ];

    public $status = 'UNPUBLISHED';
    public $lastPublishedAt = null;

    public function mount(CmsService $cms)
    {
        $version = $cms->getDraftOrPublishedContent('global');
        
        if ($version) {
            $this->content = array_merge($this->content, $version->content ?? []);
            $this->seoMeta = array_merge($this->seoMeta ?? [], $version->seo_meta ?? []);
            $this->status = $version->status;

            if ($this->status === 'DRAFT') {
                $page = \App\Models\CmsPage::where('slug', 'global')->first();
                $pub = $page ? $page->publishedVersion()->first() : null;
                $this->lastPublishedAt = $pub ? $pub->created_at->diffForHumans() : 'Never';
            } else {
                $this->lastPublishedAt = $version->created_at->diffForHumans();
            }
        }
    }

    public function saveDraft(CmsService $cms)
    {
        $cms->saveDraft('global', $this->content, $this->seoMeta);
        session()->flash('message', 'Global settings draft saved successfully.');
        $this->mount($cms);
    }

    public function publish(CmsService $cms)
    {
        $cms->saveDraft('global', $this->content, $this->seoMeta);
        $cms->publishDraft('global');
        session()->flash('message', 'Global settings published successfully.');
        $this->mount($cms);
    }

    public function render()
    {
        return view('livewire.admin.settings.index')->title('Global Settings & Footer - Vanniyan CMS');
    }
}
