@props(['title', 'description', 'action' => null, 'actionLabel' => null, 'icon' => 'cube'])

<div class="w-full bg-white border border-gray-200 rounded-lg p-12 text-center flex flex-col items-center justify-center">
    
    <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-6">
        @if($icon === 'cube')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        @elseif($icon === 'search')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        @elseif($icon === 'calendar')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        @elseif($icon === 'shopping-bag')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        @endif
    </div>

    <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">{{ $title }}</h3>
    <p class="text-gray-500 mb-8 max-w-sm">{{ $description }}</p>

    @if($action && $actionLabel)
        <a href="{{ $action }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded hover:bg-gray-50 transition-colors">
            {{ $actionLabel }}
        </a>
    @endif
</div>
