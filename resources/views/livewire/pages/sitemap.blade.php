<div>
    <!-- Hero -->
    <div class="relative bg-vanniyan-green-900 pt-16 pb-14 md:pt-20 md:pb-20">
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 20% 30%, #B48735 0, transparent 40%), radial-gradient(circle at 80% 70%, #B48735 0, transparent 35%);"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block text-xs font-bold text-vanniyan-gold uppercase tracking-[0.25em] mb-4">Vanniyan Restaurant</span>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white">Sitemap</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mt-6 mb-6"></div>
            <p class="text-base md:text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto">Explore the Vanniyan Restaurant website and find our menu, offers, booking, stories, contact information and legal pages.</p>
        </div>
    </div>

    <!-- Links -->
    <div class="bg-vanniyan-white py-14 md:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-12">

                <!-- Main -->
                <section>
                    <h2 class="font-serif text-2xl font-bold text-vanniyan-green-900 mb-1">Main</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3">
                        <li><a href="{{ route('home') }}" class="sitemap-link">Home</a></li>
                        <li><a href="{{ route('menu') }}" class="sitemap-link">Menu</a></li>
                        <li><a href="{{ route('offers') }}" class="sitemap-link">Offers &amp; Rewards</a></li>
                        <li><a href="{{ route('booking.selection') }}" class="sitemap-link">Booking</a></li>
                        <li><a href="{{ route('our-story') }}" class="sitemap-link">Our Story</a></li>
                        <li><a href="{{ route('contact') }}" class="sitemap-link">Contact</a></li>
                    </ul>
                </section>

                <!-- Booking -->
                <section>
                    <h2 class="font-serif text-2xl font-bold text-vanniyan-green-900 mb-1">Bookings</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <ul class="space-y-3">
                        <li><a href="{{ route('booking.selection') }}" class="sitemap-link">Choose How to Book</a></li>
                        <li><a href="{{ route('reservation') }}" class="sitemap-link">Reserve a Table</a></li>
                        <li><a href="{{ route('venue.booking') }}" class="sitemap-link">Book the Venue</a></li>
                    </ul>
                </section>

                <!-- Stories -->
                <section>
                    <h2 class="font-serif text-2xl font-bold text-vanniyan-green-900 mb-1">Our Stories</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <ul class="space-y-3">
                        <li><a href="{{ route('our-story') }}" class="sitemap-link">The Vanniyan Story</a></li>
                        @forelse($stories as $story)
                            <li><a href="{{ $story['url'] }}" class="sitemap-link">{{ $story['title'] }}</a></li>
                        @empty
                            <li class="text-gray-500 text-sm">More stories coming soon.</li>
                        @endforelse
                    </ul>
                </section>

                <!-- Legal -->
                <section>
                    <h2 class="font-serif text-2xl font-bold text-vanniyan-green-900 mb-1">Legal</h2>
                    <div class="w-10 h-[2px] bg-vanniyan-gold mb-6"></div>
                    <ul class="space-y-3">
                        <li><a href="{{ route('privacy-policy') }}" class="sitemap-link">Privacy Policy</a></li>
                        <li><a href="{{ route('terms-and-conditions') }}" class="sitemap-link">Terms &amp; Conditions</a></li>
                        <li><a href="{{ route('sitemap.page') }}" class="sitemap-link">Sitemap</a></li>
                    </ul>
                </section>

            </div>
        </div>
    </div>
</div>
