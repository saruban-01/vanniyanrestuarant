<?php

namespace App\Livewire\Admin\Seo;

use App\Models\Story;
use App\Models\Offer;
use App\Models\SeoMetadata;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Health extends Component
{
    public $issues = [];

    public function mount()
    {
        $this->runChecks();
    }

    public function runChecks()
    {
        $this->issues = [];

        // Check 1: Missing Global Settings
        $globalTitle = \App\Models\RestaurantSetting::where('key', 'seo_default_title')->value('value');
        if (!$globalTitle) {
            $this->issues[] = [
                'type' => 'critical',
                'message' => 'Missing Default Site Title in Global Settings.',
                'action' => route('admin.seo.global'),
                'action_text' => 'Fix Now'
            ];
        }

        // Check 2: Canonical base not set
        $canonicalBase = \App\Models\RestaurantSetting::where('key', 'seo_canonical_base')->value('value');
        if (!$canonicalBase) {
            $this->issues[] = [
                'type' => 'warning',
                'message' => 'Canonical Base URL not explicitly set.',
                'action' => route('admin.seo.global'),
                'action_text' => 'Set Base URL'
            ];
        }

        // Check 3: Check static pages for missing meta descriptions
        $staticRoutes = ['home', 'menu', 'offers', 'reservation', 'contact'];
        foreach ($staticRoutes as $route) {
            $meta = SeoMetadata::where('route_name', $route)->first();
            if (!$meta || empty($meta->meta_description)) {
                $this->issues[] = [
                    'type' => 'warning',
                    'message' => "Static page '{$route}' is missing a specific meta description.",
                    'action' => route('admin.seo.pages'),
                    'action_text' => 'Edit Page SEO'
                ];
            }
        }
        
        // Similar checks could be added for Stories and Offers.
    }

    public function render()
    {
        return view('livewire.admin.seo.health')->title('SEO Health - Vanniyan Admin');
    }
}
