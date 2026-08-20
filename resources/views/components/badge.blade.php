@props([
    'variant' => 'green'
])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
    
    $variants = [
        'green' => 'bg-vanniyan-green-100 text-vanniyan-green-900',
        'gold' => 'bg-vanniyan-gold-100 text-vanniyan-gold',
        'red' => 'bg-red-100 text-red-800',
        'gray' => 'bg-gray-100 text-gray-800',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
