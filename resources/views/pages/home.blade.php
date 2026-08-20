<x-layouts.app>
    <main>
        <!-- 01. Hero -->
        <x-site.hero :content="$content" />

        <!-- 02. Signature Dishes -->
        <section class="py-24 bg-vanniyan-white">
            <div class="max-w-[1280px] mx-auto px-6 md:px-12">
                <x-site.section-heading 
                    title="Our Signatures" 
                    subtitle="A few flavours that define the Vanniyan table."
                    align="center"
                    eyebrow="Signature Dishes"
                />
                
                <div class="mt-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @php
                        $sigIds = $content['signature_dishes'] ?? [];
                        $signatures = \App\Models\MenuItem::whereIn('id', $sigIds)->where('is_active', true)->get();
                        if ($signatures->isEmpty()) {
                            $signatures = \App\Models\MenuItem::where('is_signature', true)->where('is_active', true)->get();
                        }
                    @endphp
                    @forelse($signatures as $item)
                        <x-site.food-card 
                            name="{{ $item->name }}" 
                            description="{{ $item->description }}" 
                            price="Rs. {{ number_format($item->price, 2) }}" 
                            image="{{ $item->image_url ?: 'https://images.unsplash.com/photo-1633383718081-22ac93e3db65?auto=format&fit=crop&q=80&w=800' }}"
                            isSignature="true"
                        />
                    @empty
                        <!-- Fallback if no signature dishes selected -->
                        <x-site.food-card 
                            name="Vanni Mutton Biryani" 
                            description="Fragrant basmati cooked with tender mutton pieces, traditional spices, and love." 
                            price="Rs. 2,500" 
                            image="https://images.unsplash.com/photo-1633383718081-22ac93e3db65?auto=format&fit=crop&q=80&w=800"
                            isSignature="true"
                        />
                    @endforelse
                </div>
            </div>
        </section>

        <!-- 03. Featured Offer -->
        <section class="py-24 bg-[#F7F7F5]">
            <div class="max-w-[1280px] mx-auto px-6 md:px-12">
                <x-site.section-heading 
                    title="Special at Vanniyan" 
                    subtitle="Something special for your next visit."
                    eyebrow="This Week"
                />
                
                <div class="mt-12">
                    <x-site.featured-offer />
                </div>
            </div>
        </section>

        <!-- 04. Most Ordered (from takeaway data) -->
        <x-site.popular-dishes />

        <!-- 04. Book With Vanniyan -->
        <section class="py-24 bg-vanniyan-green-900 text-white text-center">
            <div class="max-w-[1280px] mx-auto px-6 md:px-12">
                <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-4 block">Reservations &amp; Venue</span>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-white mb-5">
                    Book With Vanniyan
                </h2>
                <div class="w-12 h-px bg-vanniyan-gold mx-auto mb-7"></div>
                <p class="text-lg text-gray-300 max-w-2xl mx-auto font-light mb-10">
                    Reserve a table for dining or book our available venue space for your own function.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="{{ route('booking.selection') }}" class="bg-white text-vanniyan-green-900 hover:bg-gray-100 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-8 py-3.5">
                        Reserve Now
                    </a>
                    <a href="{{ route('venue.booking') }}" class="border border-white/70 text-white hover:bg-white hover:text-vanniyan-green-900 transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-8 py-3.5">
                        Book Venue
                    </a>
                </div>
            </div>
        </section>

        <!-- 05. Choose Your Experience -->
        <section class="py-24 bg-vanniyan-white border-t border-gray-100">
            <div class="max-w-[1280px] mx-auto px-6 md:px-12">
                <x-site.section-heading 
                    title="Choose Your Experience" 
                    align="center"
                    eyebrow="How Will You Dine"
                />
                
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <x-site.experience-card 
                        :title="$content['exp_dinein_title'] ?? 'Dine-In'" 
                        :description="$content['exp_dinein_text'] ?? 'Enjoy the food, atmosphere and hospitality at Vanniyan.'" 
                        :cta="$content['exp_dinein_cta_text'] ?? 'View Menu'"
                        :url="$content['exp_dinein_cta_url'] ?? route('menu')"
                        icon="dine"
                    />
                    <x-site.experience-card 
                        :title="$content['exp_takeaway_title'] ?? 'Takeaway'" 
                        :description="$content['exp_takeaway_text'] ?? 'Order your favourites and collect them from the restaurant.'" 
                        :cta="$content['exp_takeaway_cta_text'] ?? 'Order Takeaway'"
                        :url="$content['exp_takeaway_cta_url'] ?? route('takeaway')"
                        icon="takeaway"
                    />
                    <x-site.experience-card 
                        :title="$content['exp_events_title'] ?? 'Venue'" 
                        :description="$content['exp_events_text'] ?? 'Book Vanniyan\'s venue space for your own event.'" 
                        :cta="$content['exp_events_cta_text'] ?? 'Book Venue'"
                        :url="$content['exp_events_cta_url'] ?? route('venue.booking')"
                        icon="venue"
                    />
                </div>
            </div>
        </section>

        <!-- 06. Guest Experiences (Google Reviews) -->
        <x-site.guest-experiences
            :enabled="$googleReviewsEnabled"
            :data="$googleReviews"
            :heading="$content['google_reviews_heading'] ?? 'Loved by our guests'"
            :subtitle="$content['google_reviews_subtitle'] ?? 'Real experiences from people who have visited Vanniyan Restaurant.'"
            :readUrl="$googleReviewsUrl"
            :writeUrl="$googleReviewsWriteUrl"
        />

        <!-- 07. Our Story -->
        <x-site.story-preview :content="$content" />

        <!-- 07. Cultural Story -->
        <x-site.cultural-story :content="$content" />

        <!-- 08. Reservation CTA -->
        <x-site.reservation-cta :content="$content" />

        <!-- 10. Location -->
        <x-site.location-preview :content="$content" />

        <!-- 11. Final CTA -->
        <x-site.final-cta :content="$content" />

    </main>
</x-layouts.app>
