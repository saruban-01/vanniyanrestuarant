<section class="bg-white py-16 md:py-24 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <!-- Image -->
            <div class="w-full lg:w-1/2">
                <div class="relative rounded-2xl overflow-hidden shadow-xl group">
                    <img src="{{ $offer->image_url ?: 'https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&q=80&w=1200' }}"
                         alt="{{ $offer->title }}"
                         class="w-full h-[380px] md:h-[480px] object-cover transform group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-6 left-6 z-10 flex flex-col gap-2">
                        <span class="bg-white/90 backdrop-blur-sm text-vanniyan-green-900 text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">Featured Special</span>
                        @if($offer->is_takeaway)
                            <span class="bg-vanniyan-green-900/90 backdrop-blur-sm text-white text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm w-max">Takeaway</span>
                        @endif
                        @if($offer->is_dine_in)
                            <span class="bg-white/90 backdrop-blur-sm text-vanniyan-green-900 text-[11px] font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm w-max">Dine-In</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="w-full lg:w-1/2">
                <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-4">Featured Special</span>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-vanniyan-green-900 mb-6 leading-tight">{{ $offer->title }}</h2>
                <div class="w-10 h-[2px] bg-vanniyan-gold mb-7"></div>

                @if($offer->price_or_discount)
                    <div class="inline-block bg-vanniyan-green-100 text-vanniyan-green-900 font-bold px-5 py-2 rounded-full text-base mb-7">
                        {{ $offer->price_or_discount }}
                    </div>
                @endif

                <p class="text-lg text-gray-600 font-light mb-8 leading-relaxed">{{ $offer->description }}</p>

                @if($offer->valid_until)
                    <p class="text-sm text-gray-400 mb-9 flex items-center gap-2">
                        <svg class="w-4 h-4 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold uppercase tracking-wider">Valid until {{ $offer->valid_until->format('j F Y') }}</span>
                    </p>
                @endif

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('contact') }}" class="bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-bold text-sm uppercase tracking-wider rounded-full px-8 py-3.5 shadow-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Get Directions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>