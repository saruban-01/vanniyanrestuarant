<?php

namespace App\Livewire\Admin\Website;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Services\CmsService;

#[Layout('components.layouts.admin')]
class HomeEditor extends Component
{
    public array $content = [
        'hero_eyebrow' => 'A Taste of the Vanni Kingdom',
        'hero_h1' => 'Vanniyan',
        'hero_text' => 'Experience authentic heritage cuisine in the heart of London.',
        'hero_cta_primary_text' => 'Reserve a Table',
        'hero_cta_primary_url' => '/reservation',
        'hero_cta_secondary_text' => 'View Menu',
        'hero_cta_secondary_url' => '/menu',
        
        'signature_dishes' => [], // array of IDs
        'featured_offer_id' => null,
        
        'exp_dinein_title' => 'Dine-In',
        'exp_dinein_text' => 'Join us for a royal feast.',
        'exp_dinein_cta_text' => 'Reserve',
        'exp_dinein_cta_url' => '/reservation',
        
        'exp_takeaway_title' => 'Takeaway',
        'exp_takeaway_text' => 'Heritage flavors at home.',
        'exp_takeaway_cta_text' => 'Order Now',
        'exp_takeaway_cta_url' => '/takeaway',
        
        'exp_events_title' => 'Venue',
        'exp_events_text' => 'Book Vanniyan\'s venue space for your own event.',
        'exp_events_cta_text' => 'Book Venue',
        'exp_events_cta_url' => '/booking',
        
        'story_label' => 'Our Roots',
        'story_heading' => 'The Legend of Vanni',
        'story_excerpt' => 'Our recipes have been passed down through generations...',
        'story_cta_text' => 'Read Our Story',
        'story_cta_url' => '/our-story',
        
        'cultural_story_id' => null,
        
        'loyalty_heading' => 'Vanniyan Rewards',
        'loyalty_text' => 'Pick up your physical card in store.',
        'loyalty_visit_5' => 'Free Drink',
        'loyalty_visit_10' => 'Rs. 1,000 Food Coupon',
        
        'location_heading' => 'Find Us',
        'location_text' => 'Located in Central London.',
    ];

    public ?array $seoMeta = [
        'title' => 'Vanniyan - A Taste of the Vanni Kingdom',
        'description' => 'Authentic Vanni heritage cuisine in London.',
    ];

    public $status = 'UNPUBLISHED';
    public $lastPublishedAt = null;

    public function mount(CmsService $cms)
    {
        $version = $cms->getDraftOrPublishedContent('home');
        
        if ($version) {
            $this->content = array_merge($this->content, $version->content ?? []);
            $this->seoMeta = array_merge($this->seoMeta ?? [], $version->seo_meta ?? []);
            $this->status = $version->status; // DRAFT or PUBLISHED

            if ($this->status === 'DRAFT') {
                $page = \App\Models\CmsPage::where('slug', 'home')->first();
                $pub = $page ? $page->publishedVersion()->first() : null;
                $this->lastPublishedAt = $pub ? $pub->created_at->diffForHumans() : 'Never';
            } else {
                $this->lastPublishedAt = $version->created_at->diffForHumans();
            }
        }
    }

    public function saveDraft(CmsService $cms)
    {
        // Validation could be added here
        
        $cms->saveDraft('home', $this->content, $this->seoMeta);
        
        session()->flash('message', 'Draft saved successfully.');
        $this->mount($cms);
    }

    public function publish(CmsService $cms)
    {
        $cms->saveDraft('home', $this->content, $this->seoMeta);
        $cms->publishDraft('home');
        
        session()->flash('message', 'Changes published to the live website.');
        $this->mount($cms);
    }

    public function render()
    {
        return view('livewire.admin.website.home-editor', [
            'menuItems' => \App\Models\MenuItem::where('is_active', true)->get(),
            'offers' => \App\Models\Offer::where('is_published', true)->get(),
            'stories' => \App\Models\Story::where('is_published', true)->get(),
        ])->title('Homepage Editor - Vanniyan CMS');
    }
}
