<?php

namespace App\Livewire\Pages;

use App\Services\LegalService;
use App\Services\RestaurantSettingsService;
use Livewire\Component;

class TermsConditionsPage extends Component
{
    public function render(LegalService $legal, RestaurantSettingsService $settings)
    {
        return view('livewire.pages.terms-conditions', [
            'content' => $legal->published(LegalService::DOC_TERMS),
            'headings' => $legal->headings(LegalService::DOC_TERMS),
            'publishedAt' => $legal->publishedAt(LegalService::DOC_TERMS),
            'contact' => [
                'name' => $settings->get('name'),
                'phone' => $settings->get('phone'),
                'email' => $settings->get('email'),
            ],
        ])->layout('components.layouts.app');
    }
}
