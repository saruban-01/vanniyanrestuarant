@props([
    'variant' => 'default'
])

@php
    $baseClasses = 'bg-white rounded-lg overflow-hidden';
    
    $variants = [
        'default' => 'border border-gray-100',
        'flat' => '',
        'elevated' => 'shadow-md',
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant];
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
