<div class="bg-white">
    <!-- SECTION 01: HERO -->
    <div class="relative w-full h-[45vh] md:h-[60vh] bg-vanniyan-green-900 flex items-center justify-center pt-16">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1600&q=80" alt="Vanniyan restaurant interior inspired by the Vanni" class="w-full h-full object-cover opacity-40 blur-[2px]">
        </div>
        <div class="absolute inset-0 bg-vanniyan-green-900/50 backdrop-blur-sm"></div>
        
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Our Story</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">From the Land of Vanni</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-7"></div>
            <p class="text-lg md:text-xl text-gray-200 font-light mb-10 max-w-2xl mx-auto">Discover the food, hospitality, traditions and stories that inspire Vanniyan.</p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#stories" class="w-full sm:w-auto px-8 py-3 bg-vanniyan-gold text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-yellow-600 transition-colors">
                    Explore Our Stories
                </a>
                <a href="{{ route('menu') }}" class="w-full sm:w-auto px-8 py-3 bg-white/10 border border-white text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-white hover:text-vanniyan-green-900 transition-colors">
                    View Menu
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION 02: OUR STORY INTRODUCTION -->
    <div class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-24">
            <div class="w-full md:w-1/2">
                <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800&q=80" alt="Traditional cooking at Vanniyan" class="w-full h-[400px] md:h-[600px] object-cover rounded-xl shadow-lg">
            </div>
            <div class="w-full md:w-1/2 max-w-[650px]">
                <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Vanniyan</span>
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-6">More Than a Meal</h2>
                <div class="text-gray-600 space-y-4 leading-relaxed text-lg">
                    <p>Vanniyan is rooted in the deep cultural landscape of the Vanni mainland. We established this space to honour the culinary heritage, the resilience of the people, and the rich history that shapes our daily lives.</p>
                    <p>Our kitchen celebrates traditional techniques, fiery spices, and the communal joy of sharing food, while our dining room reflects the quiet dignity and history of the Vanni chieftains.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 03: FOOD / PEOPLE / PLACE -->
    <div class="bg-[#F7F7F5] py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-4">What Defines Vanniyan</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-16">
                <!-- Food -->
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1559339352-11d035aa65de?w=600&q=80" alt="Traditional Vanni food served at Vanniyan" class="w-32 h-32 md:w-48 md:h-48 rounded-full object-cover mb-8 shadow-md">
                    <h3 class="text-xl font-bold text-vanniyan-green-900 mb-3">Food</h3>
                    <p class="text-gray-600">Flavours inspired by the traditions and ingredients of Vanni.</p>
                </div>
                
                <!-- People -->
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?w=600&q=80" alt="Hospitality at Vanniyan" class="w-32 h-32 md:w-48 md:h-48 rounded-full object-cover mb-8 shadow-md">
                    <h3 class="text-xl font-bold text-vanniyan-green-900 mb-3">People</h3>
                    <p class="text-gray-600">Hospitality is part of what makes a meal memorable.</p>
                </div>
                
                <!-- Place -->
                <div class="flex flex-col items-center text-center">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=600&q=80" alt="Vanni landscape" class="w-32 h-32 md:w-48 md:h-48 rounded-full object-cover mb-8 shadow-md">
                    <h3 class="text-xl font-bold text-vanniyan-green-900 mb-3">Place</h3>
                    <p class="text-gray-600">Vanniyan is rooted in a sense of place, memory and connection to Vanni.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 05: STORIES BEHIND THE ART -->
    <div id="stories" class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-20">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-4">Stories Behind the Art</h2>
            <p class="text-gray-600 text-lg">The artworks throughout Vanniyan are inspired by places, traditions, food and everyday life. See an artwork in the restaurant? Scan the QR code beneath it to explore the story behind the image.</p>
        </div>

        @if($featuredStory)
            <!-- Featured Cultural Story -->
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm flex flex-col md:flex-row mb-16 transition-all hover:shadow-md group">
                <div class="w-full md:w-1/2 relative h-64 md:h-auto">
                    @if($featuredStory->image)
                        <img src="{{ $featuredStory->image }}" alt="{{ $featuredStory->title }} artwork at Vanniyan Restaurant" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-gray-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
                <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
                    @if($featuredStory->category)
                        <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">{{ $featuredStory->category }}</span>
                    @endif
                    <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6 group-hover:text-vanniyan-gold transition-colors">
                        <a href="{{ url('/our-stories/' . $featuredStory->slug) }}" class="focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            {{ $featuredStory->title }}
                        </a>
                    </h3>
                    @if($featuredStory->excerpt)
                        <p class="text-gray-600 mb-8 leading-relaxed">{{ $featuredStory->excerpt }}</p>
                    @endif
                    <div>
                        <span class="inline-flex items-center text-sm font-bold uppercase tracking-wider text-vanniyan-green-900 group-hover:text-vanniyan-gold transition-colors">
                            Read The Story
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </div>
            </div>
        @endif

        @if($stories->count() > 0)
            <!-- Story Collection -->
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Explore Vanniyan Stories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($stories as $story)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group flex flex-col h-full relative">
                        <div class="h-48 bg-gray-100 relative shrink-0">
                            @if($story->image)
                                <img src="{{ $story->image }}" alt="{{ $story->title }}" class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            @if($story->category)
                                <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-2">{{ $story->category }}</span>
                            @endif
                            <h3 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-3 group-hover:text-vanniyan-gold transition-colors">
                                <a href="{{ url('/our-stories/' . $story->slug) }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    {{ $story->title }}
                                </a>
                            </h3>
                            @if($story->excerpt)
                                <p class="text-sm text-gray-600 mb-6 flex-grow">{{ $story->excerpt }}</p>
                            @endif
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <span class="text-xs font-bold uppercase tracking-wider text-vanniyan-green-900 group-hover:text-vanniyan-gold transition-colors flex items-center">
                                    Read Story &rarr;
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!$featuredStory && $stories->count() === 0)
            <!-- Empty State -->
            <div class="text-center py-16 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-4">The Stories Are Taking Shape</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">Discover the Vanniyan experience through our food, place, and hospitality while we curate our cultural stories.</p>
                <a href="{{ route('menu') }}" class="inline-block bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm px-8 py-3 rounded hover:bg-vanniyan-green-800 transition-colors">
                    View Menu
                </a>
            </div>
        @endif
    </div>

    <!-- SECTION 06: QR EXPERIENCE -->
    <div class="bg-[#F7F7F5] py-16 md:py-24 border-y border-gray-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-16 h-16 mx-auto bg-vanniyan-green-900 rounded-2xl flex items-center justify-center mb-8 shadow-sm">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-6">See the Art. Scan the Story.</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Many of the stories represented around Vanniyan continue beyond the walls of the restaurant. Scan the QR code beneath an artwork to discover its story on your phone.
            </p>
        </div>
    </div>

    <!-- SECTION 07: FOOD CONNECTION -->
    <div class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center gap-12 lg:gap-24">
            <div class="w-full md:w-1/2 max-w-[650px] order-2 md:order-1">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-6">From Story to Table</h2>
                <p class="text-gray-600 space-y-4 leading-relaxed text-lg mb-8">
                    The stories that inspire Vanniyan are also connected to the flavours and experiences we create at the table. Every ingredient, spice, and technique we use is a reflection of this heritage.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('menu') }}" class="w-full sm:w-auto text-center px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-vanniyan-green-800 transition-colors">
                        Explore the Menu
                    </a>
                </div>
            </div>
            <div class="w-full md:w-1/2 order-1 md:order-2">
                <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=800&q=80" alt="Authentic traditional dish at Vanniyan" class="w-full h-[400px] object-cover rounded-xl shadow-lg">
            </div>
        </div>
    </div>

    <!-- SECTION 08: FINAL CTA -->
    <div class="bg-vanniyan-green-900 text-white py-20 md:py-24 text-center px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-6">Experience the Story for Yourself</h2>
        <p class="text-lg text-gray-300 mb-10 max-w-xl mx-auto">Come discover the food, atmosphere and hospitality of Vanniyan.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('menu') }}" class="px-8 py-3 bg-white text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded hover:bg-gray-100 transition-colors">
                View Menu
            </a>
            <a href="#" class="px-8 py-3 bg-transparent border border-white text-white font-bold uppercase tracking-wider text-sm rounded hover:bg-white hover:text-vanniyan-green-900 transition-colors">
                Reserve a Table
            </a>
        </div>
    </div>
</div>
