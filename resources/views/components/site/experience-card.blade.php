@props([
    'title',
    'description',
    'cta',
    'url',
    'icon' => 'dine'
])

@php
    $icons = [
        'dine' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v10.55A4 4 0 1014 17V7h4V3h-6zm-4 11a2 2 0 100 4 2 2 0 000-4z"></path>',
        'takeaway' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>',
        'venue' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>',
    ];
@endphp

<div class="group bg-white border border-gray-100 p-8 rounded-2xl text-center flex flex-col h-full hover:border-vanniyan-gold/30 hover:shadow-xl transition-all duration-500 hover:-translate-y-1.5">
    <svg class="w-7 h-7 mx-auto mb-5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">{!! $icons[$icon] ?? $icons['dine'] !!}</svg>
    <h3 class="text-lg font-serif font-bold text-vanniyan-green-900 mb-3">{{ $title }}</h3>
    <p class="text-gray-600 text-sm mb-8 flex-1 leading-relaxed">{{ $description }}</p>
    <a href="{{ $url }}" class="text-sm font-bold uppercase tracking-wider text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">
        {{ $cta }} &rarr;
    </a>
</div>