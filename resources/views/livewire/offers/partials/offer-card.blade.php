<div class="bg-white border border-gray-100 rounded-2xl overflow-hidden group hover:shadow-xl hover:border-vanniyan-gold/30 hover:-translate-y-1.5 transition-all duration-500 flex flex-col h-full relative">

    <!-- Image -->
    <div class="relative h-56 overflow-hidden bg-gray-100">
        <img src="{{ $offer->image_url ?: 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&q=80&w=800' }}"
             alt="{{ $offer->title }}"
             class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">

        <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
            @if($offer->type === 'discount')
                <span class="bg-white/90 backdrop-blur-sm text-vanniyan-green-900 text-[11px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Discount</span>
            @elseif($offer->type === 'free_item')
                <span class="bg-vanniyan-gold text-white text-[11px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Free Item</span>
            @else
                <span class="bg-white/90 backdrop-blur-sm text-vanniyan-green-900 text-[11px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Bundle</span>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="p-6 flex flex-col flex-1">
        @if($offer->price_or_discount)
            <div class="inline-block self-start bg-vanniyan-green-100 text-vanniyan-green-900 font-bold text-sm px-4 py-1.5 rounded-full mb-4">
                {{ $offer->price_or_discount }}
            </div>
        @endif

        <h3 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-3">{{ $offer->title }}</h3>
        <div class="w-8 h-[2px] bg-vanniyan-gold mb-4"></div>

        <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed">{{ $offer->description }}</p>

        <div class="mt-auto">
            @if($offer->valid_until)
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Valid until {{ $offer->valid_until->format('j M Y') }}
                </p>
            @endif

            @php
    $ctaLabel = $offer->cta_text ?: ($offer->is_takeaway ? 'Order Takeaway' : 'View Menu');
    $ctaHref = $offer->cta_url ?: ($offer->is_takeaway ? route('menu') . '?mode=takeaway' : route('menu'));
@endphp

            @if($offer->is_takeaway || $offer->cta_text)
                <a href="{{ $ctaHref }}" data-track-event="offer_clicked" data-track-data='{"offer_id": {{ $offer->id }}, "offer_title": {{ json_encode($offer->title, JSON_HEX_APOS) }}}' data-track-consent="marketing" class="block text-center border border-vanniyan-green-900 text-vanniyan-green-900 hover:bg-vanniyan-green-900 hover:text-white transition-colors duration-300 rounded-full px-4 py-2.5 font-bold text-sm uppercase tracking-wider">
                    {{ $ctaLabel }}
                </a>
            @endif
        </div>
    </div>
</div>