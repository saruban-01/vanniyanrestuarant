@props([
    'title',
    'eyebrow' => 'Vanniyan Restaurant',
    'subtitle' => '',
    'publishedAt' => null,
    'headings' => [],
    'content' => '',
    'contact' => [],
])

@php
    $contactName = $contact['name'] ?? null;
    $contactPhone = $contact['phone'] ?? null;
    $contactEmail = $contact['email'] ?? null;
    $hasContact = $contactName || $contactPhone || $contactEmail;
@endphp

<div>
    <!-- Hero -->
    <div class="relative bg-vanniyan-green-900 pt-16 pb-14 md:pt-20 md:pb-20">
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 20% 30%, #B48735 0, transparent 40%), radial-gradient(circle at 80% 70%, #B48735 0, transparent 35%);"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.25em] mb-4">{{ $eyebrow }}</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white">{{ $title }}</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mt-6 mb-6"></div>
            @if($subtitle)
                <p class="text-base md:text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
            @if($publishedAt)
                <p class="mt-6 inline-flex items-center gap-2 text-sm text-gray-400">
                    <svg class="w-4 h-4 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Last updated: {{ $publishedAt }}
                </p>
            @endif
        </div>
    </div>

    <!-- Body -->
    <div class="bg-vanniyan-white py-14 md:py-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-[230px_minmax(0,760px)] lg:gap-14 lg:justify-center">

                <!-- Contents (desktop: sticky sidebar) -->
                @if(count($headings) > 1)
                    <aside class="hidden lg:block">
                        <nav class="sticky top-24 max-h-[calc(100vh-8rem)] overflow-y-auto pr-2">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">On this page</p>
                            <ul class="space-y-1 border-l border-gray-200">
                                @foreach($headings as $heading)
                                    <li>
                                        <a href="#{{ $heading['id'] }}" class="block pl-4 -ml-px border-l-2 border-transparent hover:border-vanniyan-gold hover:text-vanniyan-green-900 text-sm text-gray-500 py-1.5 leading-snug transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded">
                                            {{ $heading['text'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </nav>
                    </aside>

                    <!-- Contents (mobile: collapsible) -->
                    <details class="lg:hidden mb-10 bg-[#F7F7F5] rounded-xl border border-[#E5E5E5] overflow-hidden group">
                        <summary class="px-5 py-4 flex items-center justify-between cursor-pointer list-none text-sm font-bold text-vanniyan-green-900 uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-vanniyan-gold">
                            <span>On this page</span>
                            <svg class="w-5 h-5 text-vanniyan-gold group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </summary>
                        <ul class="px-5 pb-5 space-y-1">
                            @foreach($headings as $heading)
                                <li>
                                    <a href="#{{ $heading['id'] }}" class="block py-2 text-sm text-gray-600 hover:text-vanniyan-green-900 transition-colors">
                                        {{ $heading['text'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </details>
                @endif

                <!-- Document -->
                @if($content)
                    <article class="legal-prose">
                        {!! $content !!}
                    </article>

                    @if($hasContact)
                        <div class="mt-10 bg-[#F7F7F5] border border-[#E5E5E5] rounded-xl p-6 sm:p-8">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Contact Vanniyan Restaurant</p>
                            @if($contactName)
                                <p class="font-serif text-xl font-bold text-vanniyan-green-900 mb-4">{{ $contactName }}</p>
                            @endif
                            <ul class="space-y-2 text-sm text-gray-600">
                                @if($contactPhone)
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-vanniyan-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <a href="tel:{{ str_replace(' ', '', $contactPhone) }}" class="hover:text-vanniyan-green-900 transition-colors">{{ $contactPhone }}</a>
                                    </li>
                                @endif
                                @if($contactEmail)
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-vanniyan-gold flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        <a href="mailto:{{ $contactEmail }}" class="hover:text-vanniyan-green-900 transition-colors">{{ $contactEmail }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                @else
                    <div class="bg-[#F7F7F5] border border-[#E5E5E5] rounded-xl p-10 text-center">
                        <p class="text-gray-500">This page is being prepared. Please check back soon.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
