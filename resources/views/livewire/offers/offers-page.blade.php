<div class="bg-vanniyan-white min-h-screen">

    <!-- HERO SECTION -->
    <section class="relative w-full h-[45vh] md:h-[60vh] bg-vanniyan-green-900 flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&q=80&w=1920"
                 alt="Vanniyan Specials"
                 class="w-full h-full object-cover opacity-40 blur-[2px]">
        </div>
        <div class="absolute inset-0 bg-vanniyan-green-900/50 backdrop-blur-sm"></div>

        <!-- Hero Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto pt-10 md:pt-0">
            <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Vanniyan Specials</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">
                Our Deals
            </h1>
            <p class="text-lg md:text-xl text-gray-200 font-light mb-10 max-w-2xl mx-auto">
                Discover what's special at Vanniyan and collect rewards through our physical Loyalty Card.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#special-offers" class="w-full sm:w-auto px-8 py-3 bg-vanniyan-gold text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-yellow-600 transition-colors">
                    View Specials
                </a>
                <a href="{{ route('menu') }}" class="w-full sm:w-auto px-8 py-3 bg-white/10 border border-white text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-white hover:text-vanniyan-green-900 transition-colors">
                    View Menu
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION NAVIGATION -->
    <div class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-center md:justify-start gap-8 overflow-x-auto no-scrollbar" x-data="{ activeSection: 'offers' }" @scroll.window="
                activeSection = window.scrollY > document.getElementById('loyalty-card').offsetTop - 200 ? 'loyalty' : 'offers'
            ">
                <a href="#special-offers"
                   class="whitespace-nowrap py-4 px-2 font-bold uppercase tracking-widest text-sm transition-colors border-b-2"
                   :class="activeSection === 'offers' ? 'border-vanniyan-green-900 text-vanniyan-green-900' : 'border-transparent text-gray-400 hover:text-gray-900'">
                    Special Offers
                </a>
                <a href="#loyalty-card"
                   class="whitespace-nowrap py-4 px-2 font-bold uppercase tracking-widest text-sm transition-colors border-b-2"
                   :class="activeSection === 'loyalty' ? 'border-vanniyan-green-900 text-vanniyan-green-900' : 'border-transparent text-gray-400 hover:text-gray-900'">
                    Loyalty Card
                </a>
            </div>
        </div>
    </div>

    <!-- OFFERS SECTION -->
    <div id="special-offers" class="scroll-mt-20">

        <!-- Featured Offer -->
        @if($featuredOffer)
            @include('livewire.offers.partials.featured-offer', ['offer' => $featuredOffer])
        @endif

        <!-- Active Offers Grid -->
        <section class="py-16 md:py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-14">
                    <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-4">Current Specials</span>
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900">WHAT'S ON AT VANNIYAN</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mx-auto mt-5"></div>
                </div>

                @if($activeOffers->isNotEmpty() || $featuredOffer)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($activeOffers as $offer)
                            @include('livewire.offers.partials.offer-card', ['offer' => $offer])
                        @endforeach
                    </div>
                @else
                    <!-- No Active Offers State -->
                    <div class="text-center max-w-2xl mx-auto py-12">
                        <div class="w-24 h-24 bg-white border border-gray-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-4">NOTHING SPECIAL TODAY</h2>
                        <div class="w-10 h-[2px] bg-vanniyan-gold mx-auto mb-6"></div>
                        <p class="text-lg text-gray-600 font-light mb-8">Explore our menu and discover your Vanniyan favourites.</p>
                        <div class="flex justify-center gap-4">
                            <a href="{{ route('menu') }}" class="bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 font-bold text-sm uppercase tracking-wider rounded-full px-8 py-3.5">
                                View Menu
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </div>

    <!-- LOYALTY CARD SECTION -->
    <div id="loyalty-card" class="scroll-mt-20">
        @if($loyaltyConfig && $loyaltyConfig->is_visible)
            @include('livewire.offers.partials.loyalty-section', ['config' => $loyaltyConfig])
        @endif
    </div>

    <!-- FINAL CTA -->
    <section class="bg-vanniyan-green-900 py-20 md:py-24 text-center px-6">
        <div class="max-w-3xl mx-auto">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-5">Vanniyan Restaurant</span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">COME BACK FOR MORE</h2>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-7"></div>
            <p class="text-xl text-gray-200 font-light mb-10">Enjoy the food, collect your visits and experience more of Vanniyan.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('menu') }}" class="bg-vanniyan-gold text-white hover:bg-yellow-600 transition-colors duration-300 font-bold text-sm uppercase tracking-wider rounded-full px-10 py-4 shadow-lg">
                    View Menu
                </a>
            </div>
        </div>
    </section>

</div>