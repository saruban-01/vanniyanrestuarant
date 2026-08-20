@props(['content' => []])

<section class="py-24 bg-vanniyan-white">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center gap-12 lg:gap-20">
        <!-- Image -->
        <div class="w-full md:w-1/2 relative h-[400px] lg:h-[500px] rounded-lg overflow-hidden bg-gray-100">
            <img 
                src="{{ $content['story_preview_image_url'] ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&q=80&w=1200' }}" 
                alt="Vanniyan Story" 
                class="absolute inset-0 w-full h-full object-cover"
                loading="lazy"
            >
        </div>
        
        <!-- Content -->
        <div class="w-full md:w-1/2 flex flex-col justify-center">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-4">{{ $content['story_label'] ?? 'Our Roots' }}</span>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-4">{{ $content['story_heading'] ?? 'The Legend of Vanni' }}</h2>
            <div class="w-12 h-px bg-vanniyan-gold mb-6"></div>
            <p class="text-gray-600 mb-8 text-lg leading-relaxed max-w-lg">
                {{ $content['story_excerpt'] ?? 'Born from a deep respect for Northern Sri Lankan heritage, Vanniyan is more than a restaurant.' }}
            </p>
            <div>
                <a href="{{ $content['story_cta_url'] ?? route('our-story') }}" class="inline-flex items-center justify-center bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-medium rounded-md px-8 py-3 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                    {{ $content['story_cta_text'] ?? 'Discover Our Story' }}
                </a>
            </div>
        </div>
    </div>
</section>