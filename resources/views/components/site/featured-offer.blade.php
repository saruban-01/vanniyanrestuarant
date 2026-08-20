@php
    $featuredOffer = \App\Models\Offer::active()->where('is_featured', true)->first() ?? \App\Models\Offer::active()->first();

    $categoryLabels = [
        'discount' => 'Special Offer',
        'free_item' => 'Free Item',
        'bundle' => 'Bundle Deal',
    ];

    $validityText = 'Limited time only';
    if ($featuredOffer && $featuredOffer->valid_from && $featuredOffer->valid_until) {
        $validityText = 'Valid ' . $featuredOffer->valid_from->format('M j') . ' — ' . $featuredOffer->valid_until->format('M j, Y');
    } elseif ($featuredOffer && $featuredOffer->valid_until) {
        $validityText = 'Valid until ' . $featuredOffer->valid_until->format('M j, Y');
    }
@endphp

@if($featuredOffer)
    <x-site.offer-card
        title="{{ $featuredOffer->title }}"
        category="{{ $categoryLabels[$featuredOffer->type] ?? 'Special Offer' }}"
        description="{{ $featuredOffer->description }}"
        validity="{{ $validityText }}"
        image="{{ $featuredOffer->image_url ?? 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&q=80&w=1200' }}"
        route="offers"
    />
@endif