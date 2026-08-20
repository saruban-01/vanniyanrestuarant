@props([
    'title',
    'subtitle' => null,
    'align' => 'left', // 'left' or 'center'
    'eyebrow' => null
])

<div class="{{ $align === 'center' ? 'text-center' : 'text-left' }}">
    @if($eyebrow)
        <span class="block text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-3">{{ $eyebrow }}</span>
    @endif
    <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 ">{{ $title }}</h2>
    <div class="{{ $align === 'center' ? 'mx-auto' : '' }} w-12 h-px bg-vanniyan-gold mt-5"></div>
    @if($subtitle)
        <p class="mt-5 text-gray-600 text-lg max-w-2xl {{ $align === 'center' ? 'mx-auto' : '' }}">
            {{ $subtitle }}
        </p>
    @endif
</div>
