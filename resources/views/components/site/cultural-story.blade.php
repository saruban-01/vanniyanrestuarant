@props(['content' => []])

<section class="py-24 bg-vanniyan-green-900 text-white relative overflow-hidden">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 relative z-10 flex flex-col md:flex-row items-center gap-12 lg:gap-20">
        <!-- Content -->
        <div class="w-full md:w-1/2 flex flex-col justify-center order-2 md:order-1">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-4">Vanniyan Stories</span>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-white mb-4">There's a story behind every image</h2>
            <div class="w-12 h-px bg-vanniyan-gold mb-6"></div>
            <p class="text-gray-300 mb-8 text-lg font-light leading-relaxed max-w-lg">
                Explore the stories, traditions and places represented in the artwork throughout Vanniyan.
            </p>
            
            @php
                $storyId = $content['cultural_story_id'] ?? null;
                $featuredStory = $storyId ? \App\Models\Story::find($storyId) : null;
            @endphp
            
            @if($featuredStory)
            <div class="bg-vanniyan-green-800 border border-vanniyan-green-700 p-6 rounded-lg mb-6">
                <span class="text-gray-400 text-xs font-medium uppercase tracking-wider mb-1 block">Featured Story</span>
                <h3 class="text-xl font-serif font-bold text-vanniyan-gold mb-3">{{ $featuredStory->title }}</h3>
                <a href="{{ route('our-stories.show', $featuredStory->slug) }}" class="text-white hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:underline underline-offset-4">
                    Read Story &rarr;
                </a>
            </div>
            @endif
            
            <p class="text-sm text-gray-400 max-w-md">
                See an artwork in our restaurant? Scan the QR code beneath it to discover its story.
            </p>
        </div>

        <!-- Image -->
        <div class="w-full md:w-1/2 relative h-[400px] lg:h-[500px] rounded-lg overflow-hidden order-1 md:order-2 bg-vanniyan-green-800">
            <img 
                src="{{ $content['cultural_story_image_url'] ?? 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&q=80&w=1200' }}" 
                alt="Vanni Culture" 
                class="absolute inset-0 w-full h-full object-cover"
                loading="lazy"
            >
        </div>
    </div>
</section>