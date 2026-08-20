@props(['status', 'label'])

@php
    $classes = [
        'open' => 'bg-green-50 text-green-700 border-green-200',
        'closed' => 'bg-red-50 text-red-700 border-red-200',
        'available' => 'bg-blue-50 text-blue-700 border-blue-200',
        'sold-out' => 'bg-gray-100 text-gray-500 border-gray-200',
        'default' => 'bg-gray-50 text-gray-700 border-gray-200'
    ];
    $colorClass = $classes[$status] ?? $classes['default'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider border {{ $colorClass }}">
    @if($status === 'open' || $status === 'available')
        <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 opacity-70"></span>
    @endif
    {{ $label }}
</span>
