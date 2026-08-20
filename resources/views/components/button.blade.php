@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2';
    
    $variants = [
        'primary' => 'bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 focus:ring-vanniyan-green-900',
        'secondary' => 'bg-white text-vanniyan-green-900 border border-vanniyan-green-900 hover:bg-vanniyan-green-50 focus:ring-vanniyan-green-900',
        'ghost' => 'bg-transparent text-vanniyan-green-900 hover:bg-vanniyan-green-50 focus:ring-vanniyan-green-900',
        'danger' => 'bg-red-500 text-white hover:bg-red-600 focus:ring-red-500',
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
