@php
    $popular = \App\Models\TakeawayOrderItem::query()
        ->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))
        ->selectRaw('item_name_snapshot as name, MAX(unit_price_snapshot) as price, SUM(quantity) as total')
        ->groupBy('item_name_snapshot')
        ->orderByDesc('total')
        ->orderByDesc('price')
        ->limit(3)
        ->get();

    $dishImages = \App\Models\MenuItem::query()
        ->whereNotNull('image_url')
        ->get(['name', 'image_url'])
        ->mapWithKeys(fn ($item) => [strtolower(trim($item->name)) => $item->image_url]);

    $dishImages = $popular->mapWithKeys(function ($dish) use ($dishImages) {
        $key = strtolower(trim($dish->name));
        $image = $dishImages[$key] ?? null;
        if (!$image) {
            $image = $dishImages->first(fn ($url, $name) => str_contains($name, $key) || str_contains($key, $name));
        }
        return [$dish->name => $image];
    });
@endphp

@if($popular->isNotEmpty())
<section class="py-24 bg-vanniyan-green-50">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12">
        <x-site.section-heading
            title="Most Ordered"
            subtitle="The dishes our takeaway customers love the most."
            align="center"
            eyebrow="Customer Favourites"
        />

        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($popular as $index => $dish)
                <div class="group bg-white rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 p-8 text-center flex flex-col h-full relative overflow-hidden">
                    <div class="absolute top-4 right-4 w-8 h-8 rounded-full bg-vanniyan-green-900 text-vanniyan-gold flex items-center justify-center text-sm font-semibold">
                        {{ $index + 1 }}
                    </div>

                    <div class="w-20 h-20 mx-auto mb-5 rounded-2xl overflow-hidden bg-vanniyan-green-50 flex items-center justify-center ring-1 ring-gray-100">
                        @if($dishImages[$dish->name] ?? null)
                            <img src="{{ $dishImages[$dish->name] }}" alt="{{ $dish->name }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21c-4.42 0-8-2.69-8-6 0-2.13 1.19-4.02 3.08-5.34C8.22 8.11 10 6.6 10.5 4.5c2.9 1.1 5.5 4.4 5.5 8.5 0 .9-.13 1.75-.36 2.55.55-.66.86-1.65.86-2.8 0-1.8-.72-3.5-1.94-4.75.8 1.7 1.44 3.7 1.44 5.75 0 2.4-1.3 4.4-3.3 5.7.44.3 1 .5 1.8.5 2.1 0 3.5-1.3 3.5-3.3 0-1.7-1.2-3-2.7-3.5.35.6.7 1.3.7 2 0 1.1-.9 1.8-1.8 1.8-.9 0-1.6-.7-1.6-1.6 0-.9.5-1.6 1-2.1.3-.3.5-.7.5-1.1 0-.6-.5-1-1-1-.9 0-1.6.7-1.6 1.6 0 1.2.4 2.4.4 3.5 0 1.1-.7 2.5-2.5 2.5-1.2 0-2-.8-2-1.8 0-.8.4-1.5 1-1.8-.6.5-1 1.2-1 2 0 1.5 1.4 2.6 3.5 2.6 1.3 0 2.5-.6 3.3-1.5.6.9 1.6 1.5 2.7 1.5 2 0 3.5-1.6 3.5-3.6 0-1.5-.9-2.8-2.2-3.4z"></path>
                            </svg>
                        @endif
                    </div>

                    <h3 class="text-lg font-serif font-bold text-vanniyan-green-900 mb-4">{{ $dish->name }}</h3>

                    <div class="mt-auto pt-5 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-vanniyan-green-900 font-bold">Rs. {{ number_format($dish->price, 0) }}</span>
                        <span class="inline-flex items-center text-xs text-gray-500 font-medium uppercase tracking-wider">
                            <svg class="w-3.5 h-3.5 mr-1 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0l3 3m-3-3l-3 3m9-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ number_format($dish->total) }} sold
                        </span>
                    </div>

                    <a href="{{ route('menu', ['mode' => 'takeaway']) }}" class="mt-5 inline-flex items-center justify-center bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-6 py-2.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                        Order Now
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif