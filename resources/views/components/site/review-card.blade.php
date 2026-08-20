@props([
    'author' => 'Google User',
    'rating' => 5,
    'text' => '',
    'relativeTime' => '',
    'url' => null,
])

<div class="flex flex-col h-full bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 p-8"
     x-data="{ expanded: false, needsToggle: {{ strlen($text) > 280 ? 'true' : 'false' }} }">

    <div class="flex items-center justify-between mb-5">
        <span class="inline-flex items-center gap-1.5 text-gray-500">
            <svg class="w-4 h-4" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span class="text-xs font-semibold uppercase tracking-wider">Google</span>
        </span>

        <span class="text-vanniyan-gold text-base tracking-tight" role="img"
              aria-label="{{ $rating }} out of 5 stars">
            @for ($i = 1; $i <= 5; $i++)
                {{ $i <= $rating ? '★' : '☆' }}
            @endfor
        </span>
    </div>

    <div class="flex-1">
        <blockquote class="text-gray-700 leading-relaxed text-[15px]">
            <span x-show="!expanded" class="line-clamp-4">“{{ $text }}”</span>
            <span x-show="expanded" x-cloak>“{{ $text }}”</span>
        </blockquote>

        @if (strlen($text) > 280)
            <button
                type="button"
                x-show="needsToggle"
                @click="expanded = !expanded"
                :aria-expanded="expanded ? 'true' : 'false'"
                class="mt-3 text-vanniyan-green-900 font-semibold text-sm hover:text-vanniyan-gold transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded"
            >
                <span x-show="!expanded">Read more →</span>
                <span x-show="expanded" x-cloak>Show less</span>
            </button>
        @endif
    </div>

    <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between gap-3">
        <div class="min-w-0">
            <p class="text-vanniyan-green-900 font-semibold text-sm truncate">{{ $author }}</p>
            @if ($relativeTime)
                <p class="text-xs text-gray-500 mt-0.5">{{ $relativeTime }}</p>
            @endif
        </div>
        @if ($url)
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
               class="flex-shrink-0 inline-flex items-center gap-1 text-vanniyan-gold hover:text-vanniyan-green-900 text-xs font-bold uppercase tracking-wider transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-2 py-1"
               aria-label="View this review on Google Maps">
                View on Google →
            </a>
        @endif
    </div>
</div>