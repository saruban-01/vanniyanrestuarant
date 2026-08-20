@props(['content' => []])

<section class="py-24 bg-vanniyan-green-900 text-white text-center px-6 border-t border-vanniyan-green-800">
    <div class="max-w-3xl mx-auto">
        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-5">{{ $content['reservation_heading'] ?? 'Your Table Awaits' }}</h2>
        <div class="w-12 h-px bg-vanniyan-gold mx-auto mb-8"></div>
        <p class="text-gray-300 text-lg md:text-xl mb-10 font-light">
            {{ $content['reservation_text'] ?? 'Plan your visit and enjoy the Vanniyan dining experience.' }}
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ $content['reservation_cta_primary_url'] ?? route('reservation') }}" class="w-full sm:w-auto bg-white text-vanniyan-green-900 hover:bg-gray-100 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-8 py-3.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-vanniyan-green-900 focus:ring-white">
                {{ $content['reservation_cta_primary'] ?? 'Reserve a Table' }}
            </a>
            <a href="{{ $content['reservation_cta_secondary_url'] ?? route('menu') }}" class="w-full sm:w-auto bg-transparent text-white border border-white/60 hover:bg-white/10 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-8 py-3.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-vanniyan-green-900 focus:ring-white">
                {{ $content['reservation_cta_secondary'] ?? 'View Menu' }}
            </a>
        </div>
    </div>
</section>