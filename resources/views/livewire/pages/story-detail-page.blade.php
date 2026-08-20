<div class="bg-[#F7F7F5] min-h-screen">
    
    <!-- Header Spacing & Optional QR Notice -->
    <div class="pt-24 pb-8 max-w-[750px] mx-auto px-4 sm:px-6">
        @if($isFromQR)
            <div class="bg-vanniyan-green-900/10 text-vanniyan-green-900 text-sm p-4 rounded-2xl mb-8 flex items-start">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p><strong>You scanned an artwork.</strong> This is the story behind the piece you saw at Vanniyan.</p>
            </div>
        @endif

        <nav class="text-xs text-gray-500 font-medium mb-6" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-vanniyan-green-900 transition-colors">Home</a></li>
                <li><span>→</span></li>
                <li><a href="{{ route('our-story') }}" class="hover:text-vanniyan-green-900 transition-colors">Our Story</a></li>
                <li><span>→</span></li>
                <li class="text-vanniyan-green-900" aria-current="page">{{ $story->title }}</li>
            </ol>
        </nav>

        <a href="{{ route('our-story') }}" class="inline-flex items-center text-xs font-bold text-vanniyan-gold uppercase tracking-wider mb-6 hover:text-yellow-600 transition-colors">
            &larr; Back to Stories
        </a>

        @if($story->category)
            <div class="mb-4">
                <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em]">{{ $story->category }}</span>
            </div>
        @endif

        <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5 leading-tight">
                <div class="w-14 h-px bg-vanniyan-gold mb-6"></div>{{ $story->title }}</h1>
        
        @if($story->reading_time_minutes)
            <p class="text-sm text-gray-500 font-medium">{{ $story->reading_time_minutes }} min read</p>
        @endif
    </div>

    <!-- HERO IMAGE (The Scanned Artwork) -->
    @if($story->image)
        <div class="w-full h-[45vh] md:h-[70vh] bg-gray-100 relative mb-12 md:mb-20">
            <img src="{{ $story->image }}" alt="{{ $story->title }} artwork" class="absolute inset-0 w-full h-full object-cover">
        </div>
    @endif

    <!-- STORY CONTENT BLOCKS -->
    <div class="max-w-[700px] mx-auto px-4 sm:px-6">
        @if($story->blocks && is_array($story->blocks))
            <div class="space-y-10 md:space-y-16 text-lg text-gray-800 leading-relaxed font-sans">
                @foreach($story->blocks as $block)
                    
                    @if($block['type'] === 'intro')
                        <div class="text-xl md:text-2xl font-serif text-vanniyan-green-900 leading-relaxed">
                            {!! nl2br(e($block['text'])) !!}
                        </div>
                    
                    @elseif($block['type'] === 'text')
                        <p>{!! nl2br(e($block['text'])) !!}</p>
                    
                    @elseif($block['type'] === 'heading')
                        <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mt-12 block">Chapter</span>
                        <h2 class="text-3xl font-serif font-bold text-vanniyan-green-900 mt-2 mb-6">{{ $block['text'] }}</h2>
                    
                    @elseif($block['type'] === 'pull_quote')
                        <blockquote class="my-12 pl-6 md:pl-8 border-l-4 border-vanniyan-gold bg-white py-6 pr-6 rounded-r-lg shadow-sm">
                            <p class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-4 leading-tight">"{{ $block['quote'] }}"</p>
                            @if(isset($block['source']))
                                <footer class="text-sm font-bold text-gray-500 uppercase tracking-wider">— {{ $block['source'] }}</footer>
                            @endif
                        </blockquote>
                    
                    @elseif($block['type'] === 'image')
                        <figure class="my-12">
                            <img src="{{ $block['url'] }}" alt="{{ $block['caption'] ?? 'Story image' }}" class="w-full rounded-2xl shadow-sm">
                            @if(isset($block['caption']))
                                <figcaption class="text-sm text-gray-500 mt-4 text-center">{{ $block['caption'] }}</figcaption>
                            @endif
                        </figure>
                    
                    @elseif($block['type'] === 'historical_context')
                        <div class="my-12 bg-gray-50 border border-gray-100 rounded-2xl p-8">
                            <h3 class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Historical Context</h3>
                            <div class="text-gray-700 space-y-4">
                                {!! nl2br(e($block['text'])) !!}
                            </div>
                        </div>

                    @elseif($block['type'] === 'cultural_context')
                        <div class="my-12 bg-gray-50 border border-gray-100 rounded-2xl p-8">
                            <h3 class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Cultural Context</h3>
                            <div class="text-gray-700 space-y-4">
                                {!! nl2br(e($block['text'])) !!}
                            </div>
                        </div>

                    @elseif($block['type'] === 'interpretation')
                        <div class="my-12 bg-vanniyan-green-900/5 border-l-4 border-vanniyan-green-900 rounded-r-xl p-8">
                            <h3 class="text-xs font-bold text-vanniyan-green-900 uppercase tracking-[0.2em] mb-4">Artistic Interpretation</h3>
                            <p class="text-gray-700 italic text-sm mb-4">This artwork is a contemporary interpretation inspired by historical and cultural references.</p>
                            <div class="text-gray-800">
                                {!! nl2br(e($block['text'])) !!}
                            </div>
                        </div>
                    @endif

                @endforeach
            </div>
        @else
            <!-- Fallback if no blocks (e.g. older generic content) -->
            @if($story->content)
                <div class="prose prose-lg prose-green max-w-none font-sans text-gray-800 leading-relaxed">
                    {!! $story->content !!}
                </div>
            @else
                <p class="text-gray-500 italic">This story is currently being written.</p>
            @endif
        @endif

        <!-- SOURCES & REFERENCES -->
        @if($story->sources && is_array($story->sources) && count($story->sources) > 0)
            <div class="mt-20 pt-10 border-t border-gray-200">
                <h2 class="text-xl font-serif font-bold text-vanniyan-green-900 mb-6">Sources & References</h2>
                <ul class="space-y-4">
                    @foreach($story->sources as $source)
                        <li class="text-sm text-gray-600">
                            @if(isset($source['url']) && $source['url'])
                                <a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer" class="font-bold text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">{{ $source['title'] }}</a>
                            @else
                                <span class="font-bold text-gray-800">{{ $source['title'] }}</span>
                            @endif
                            @if(isset($source['author'])) — {{ $source['author'] }} @endif
                            @if(isset($source['year'])) ({{ $source['year'] }}) @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- RELATED STORIES -->
    @if($relatedStories && $relatedStories->count() > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-24 pt-16 border-t border-gray-200">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">Continue Exploring</h2>
            <div class="w-10 h-[2px] bg-vanniyan-gold mb-8"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedStories as $related)
                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-500 group flex flex-col h-full">
                        <div class="h-48 bg-gray-100 relative shrink-0">
                            @if($related->image)
                                <img src="{{ $related->image }}" alt="{{ $related->title }}" class="absolute inset-0 w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            @if($related->category)
                                <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-2">{{ $related->category }}</span>
                            @endif
                            <h3 class="text-lg font-serif font-bold text-vanniyan-green-900 mb-3 group-hover:text-vanniyan-gold transition-colors">
                                <a href="{{ url('/our-stories/' . $related->slug) }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    {{ $related->title }}
                                </a>
                            </h3>
                            @if($related->excerpt)
                                <p class="text-sm text-gray-600 flex-grow">{{ Str::limit($related->excerpt, 100) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- FROM STORY TO TABLE (Menu Connection) -->
    <div class="max-w-[700px] mx-auto px-4 sm:px-6 mt-24">
        <div class="bg-vanniyan-green-900/5 rounded-2xl p-8 md:p-12 text-center border border-vanniyan-green-900/10">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900 mb-2">From Story to Table</h2>
            <div class="w-8 h-[2px] bg-vanniyan-gold mb-4"></div>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Discover the flavours and experiences inspired by the stories behind Vanniyan.</p>
            <a href="{{ route('menu') }}" class="inline-block bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm px-8 py-3 rounded-full hover:bg-vanniyan-green-800 transition-colors">
                View Menu
            </a>
        </div>
    </div>

    <!-- FINAL CTA -->
    <div class="bg-vanniyan-green-900 text-white py-20 mt-24 text-center px-4">
        <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Experience Vanniyan</h2>
        <p class="text-gray-300 mb-10 max-w-md mx-auto">Discover the food, atmosphere and stories of Vanni at Vanniyan Restaurant.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('menu') }}" class="px-8 py-3 bg-white text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded-full hover:bg-gray-100 transition-colors">
                View Menu
            </a>
            <a href="{{ route('reservation') }}" class="px-8 py-3 bg-transparent border border-white text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-white hover:text-vanniyan-green-900 transition-colors">
                Reserve a Table
            </a>
        </div>
    </div>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Article",
      "headline": "{{ $story->title }}",
      "image": [
        "{{ $story->image }}"
       ],
      "author": {
        "@@type": "Organization",
        "name": "Vanniyan Restaurant"
      },  
      "publisher": {
        "@@type": "Organization",
        "name": "Vanniyan Restaurant",
        "logo": {
          "@@type": "ImageObject",
          "url": "{{ url('/logo.png') }}"
        }
      },
      "description": "{{ $story->excerpt }}"
    }
    </script>

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "BreadcrumbList",
      "itemListElement": [{
        "@@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ route('home') }}"
      },{
        "@@type": "ListItem",
        "position": 2,
        "name": "Our Story",
        "item": "{{ route('our-story') }}"
      },{
        "@@type": "ListItem",
        "position": 3,
        "name": "{{ $story->title }}"
      }]
    }
    </script>

</div>
