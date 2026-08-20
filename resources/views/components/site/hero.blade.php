@props(['content' => []])

<!-- Video Hero Section -->
<section class="relative h-screen w-full overflow-hidden bg-vanniyan-green-900">
    <!-- Background Video -->
    <video
        class="absolute inset-0 w-full h-full object-cover"
        autoplay
        muted
        loop
        playsinline
        preload="metadata"
    >
        <source src="{{ asset('videos/hero.mp4') }}" type="video/mp4">
    </video>

    <!-- Cinematic Overlays -->
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/20 to-vanniyan-green-900/90"></div>

    <!-- Hero Text Overlay -->
    <div class="relative z-10 h-full w-full flex items-center justify-center">
        <div class="text-center px-6 md:px-12 max-w-4xl mx-auto flex flex-col items-center">
            <!-- Eyebrow -->
            <span class="text-xs md:text-sm font-bold text-vanniyan-gold uppercase tracking-[0.3em] mb-6 drop-shadow">{{ $content['hero_eyebrow'] ?? 'Vanniyan Restaurant' }}</span>

            <!-- H1 -->
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-bold text-white uppercase tracking-wide leading-tight mb-5 drop-shadow-lg">
                {!! nl2br(e($content['hero_h1'] ?? 'Taste the Vanni Experience')) !!}
            </h1>

            <!-- Thin gold line -->
            <div class="w-16 h-px bg-vanniyan-gold mb-8"></div>

            <!-- Supporting Text -->
            <p class="text-lg md:text-xl text-white font-light max-w-2xl mx-auto mb-12 leading-relaxed drop-shadow uppercase">
                {{ $content['hero_text'] ?? 'DINE-IN · TAKEAWAY · TABLE RESERVATION · VENUE BOOKING' }}
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5">
                <a href="{{ $content['hero_cta_primary_url'] ?? route('reservation') }}" class="w-full sm:w-auto bg-white text-vanniyan-green-900 hover:bg-vanniyan-gold hover:text-white transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-4 text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black/50 focus:ring-white">
                    {{ $content['hero_cta_primary_text'] ?? 'Reserve a Table' }}
                </a>
                <a href="{{ $content['hero_cta_secondary_url'] ?? route('menu') }}" class="w-full sm:w-auto text-white border border-white/60 hover:bg-white/10 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-4 text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black/50 focus:ring-white">
                    {{ $content['hero_cta_secondary_text'] ?? 'View Menu' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center animate-bounce text-white/70">
        <span class="text-sm tracking-widest uppercase font-bold mb-2">Scroll</span>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </div>
</section>