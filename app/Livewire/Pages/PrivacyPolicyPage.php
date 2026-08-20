<?php

namespace App\Livewire\Pages;

use App\Services\LegalService;
use App\Services\RestaurantSettingsService;
use Livewire\Component;

class PrivacyPolicyPage extends Component
{
    public function render(LegalService $legal, RestaurantSettingsService $settings)
    {
        return view('livewire.pages.privacy-policy', [
            'content' => $legal->published(LegalService::DOC_PRIVACY),
            'headings' => $legal->headings(LegalService::DOC_PRIVACY),
            'publishedAt' => $legal->publishedAt(LegalService::DOC_PRIVACY),
            'contact' => [
                'name' => $settings->get('name'),
                'phone' => $settings->get('phone'),
                'email' => $settings->get('email'),
            ],
        ])->layout('components.layouts.app');
    }
}
