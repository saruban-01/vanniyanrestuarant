<?php

namespace App\Livewire\Admin\Seo;

use App\Models\RestaurantSetting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class GlobalSettings extends Component
{
    public $seo_default_title;
    public $seo_default_description;
    public $seo_canonical_base;
    public $seo_default_og_image;

    public function mount()
    {
        $this->seo_default_title = RestaurantSetting::where('key', 'seo_default_title')->value('value') ?? 'Vanniyan Restaurant';
        $this->seo_default_description = RestaurantSetting::where('key', 'seo_default_description')->value('value') ?? '';
        $this->seo_canonical_base = RestaurantSetting::where('key', 'seo_canonical_base')->value('value') ?? config('app.url');
        $this->seo_default_og_image = RestaurantSetting::where('key', 'seo_default_og_image')->value('value') ?? '';
    }

    public function save()
    {
        $this->validate([
            'seo_default_title' => 'required|string|max:255',
            'seo_default_description' => 'required|string|max:500',
            'seo_canonical_base' => 'required|url|max:255',
            'seo_default_og_image' => 'nullable|string|max:255',
        ]);

        RestaurantSetting::updateOrCreate(['key' => 'seo_default_title'], ['value' => $this->seo_default_title]);
        RestaurantSetting::updateOrCreate(['key' => 'seo_default_description'], ['value' => $this->seo_default_description]);
        RestaurantSetting::updateOrCreate(['key' => 'seo_canonical_base'], ['value' => $this->seo_canonical_base]);
        RestaurantSetting::updateOrCreate(['key' => 'seo_default_og_image'], ['value' => $this->seo_default_og_image]);

        \App\Models\AuditLog::log(
            \Illuminate\Support\Facades\Auth::guard('admin')->user(),
            'seo_global_settings_updated',
            'Global SEO settings updated.'
        );

        session()->flash('success', 'Global SEO settings updated.');
    }

    public function render()
    {
        return view('livewire.admin.seo.global-settings')->title('Global SEO Settings - Vanniyan Admin');
    }
}
