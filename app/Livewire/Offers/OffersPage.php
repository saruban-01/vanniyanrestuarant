<?php

namespace App\Livewire\Offers;

use App\Models\LoyaltyConfig;
use App\Models\Offer;
use Livewire\Component;

class OffersPage extends Component
{
    public function mount(): void
    {
        $offerIds = Offer::active()->pluck('id')->all();

        $this->dispatch('vanniyan-track', [
            'event' => 'offer_viewed',
            'data' => [
                'offer_ids' => $offerIds,
            ],
        ]);
    }

    public function render()
    {
        // 1. Fetch featured offer (first active featured)
        $featuredOffer = Offer::active()->where('is_featured', true)->first();

        // 2. Fetch all other active offers (excluding the featured one)
        $query = Offer::active();
        if ($featuredOffer) {
            $query->where('id', '!=', $featuredOffer->id);
        }
        $activeOffers = $query->get();

        // 3. Fetch loyalty config
        $loyaltyConfig = LoyaltyConfig::getActive();

        return view('livewire.offers.offers-page', [
            'featuredOffer' => $featuredOffer,
            'activeOffers' => $activeOffers,
            'loyaltyConfig' => $loyaltyConfig,
        ])->layout('components.layouts.app', [
            'title' => 'Vanniyan Restaurant Our Deals | Kilinochchi',
            'meta_description' => 'Discover current Vanniyan Restaurant specials and learn about the Vanniyan physical loyalty card, including a free drink on the 5th visit and a Rs. 1,000 food coupon on the 10th visit.'
        ]);
    }
}
