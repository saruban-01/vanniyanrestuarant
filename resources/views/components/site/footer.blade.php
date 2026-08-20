@inject('settingsService', 'App\Services\RestaurantSettingsService')
@inject('hoursService', 'App\Services\RestaurantHoursService')

@php
    $settings = $settingsService->getAll();
    $isOpen = $hoursService->isOpenNow();
    try {
        $globalContent = app(\App\Services\CmsService::class)->getPublishedContent('global')['content'] ?? [];
    } catch (\Throwable $e) {
        $globalContent = [];
    }
    $socialInstagram = $globalContent['social_instagram'] ?? ($settings['instagram_url'] ?? '');
    $socialFacebook  = $globalContent['social_facebook'] ?? ($settings['facebook_url'] ?? '');
    $socialTiktok    = $globalContent['social_tiktok'] ?? '';
    $socialWhatsapp  = $globalContent['social_whatsapp'] ?? null;
    if (!$socialWhatsapp) {
        $digits = preg_replace('/\D+/', '', $settings['phone'] ?? '');
        $socialWhatsapp = $digits ? ltrim($digits, '0') : '';
    }
@endphp

<footer class="bg-vanniyan-green-900 text-white pt-16 pb-8 border-t-4 border-vanniyan-gold">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-8">
            
            <!-- Brand -->
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-block mb-6">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="Vanniyan Restaurant" class="h-20 w-auto">
                </a>
                <p class="text-sm text-gray-300 uppercase tracking-widest font-semibold text-vanniyan-gold mb-6">
                    {{ $settings['tagline'] ?? 'The Royal Taste of Vanni' }}
                </p>
                
                <div class="flex items-center text-sm">
                    <span class="w-2 h-2 rounded-full mr-2 {{ $isOpen ? 'bg-green-500' : 'bg-red-500' }}"></span>
                    <span class="text-gray-300 font-bold uppercase tracking-wider">{{ $isOpen ? 'Open Now' : 'Closed Now' }}</span>
                </div>
            </div>

            <!-- Explore -->
            <div>
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Explore</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Home</a></li>
                    <li><a href="{{ route('menu') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Menu</a></li>
                    <li><a href="{{ route('offers') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Our Deals</a></li>
                    <li><a href="{{ route('our-story') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Our Story</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Contact</a></li>
                </ul>
            </div>

            <!-- Dine With Us -->
            <div>
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Dine With Us</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('menu') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Dine-In</a></li>
                    <li><a href="{{ route('menu', ['mode' => 'takeaway']) }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Takeaway</a></li>
                    <li><a href="{{ route('booking.selection') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Book Now</a></li>
                    <li><a href="{{ route('venue.booking') }}" class="text-gray-300 hover:text-vanniyan-gold transition-colors font-medium focus:outline-none focus:ring-2 focus:ring-vanniyan-gold rounded px-1 -ml-1">Book Venue</a></li>
                </ul>
            </div>

            <!-- Contact & Social -->
            <div>
                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-6">Contact</h4>
                <ul class="space-y-4 text-gray-300 text-sm mb-8">
                    @if(!empty($settings['address']))
                        <li class="leading-relaxed">{{ $settings['address'] }}@if(!empty($settings['city']) && !Str::contains($settings['address'], Str::before($settings['city'], ',')))<br>{{ $settings['city'] }}@endif</li>
                    @endif
                    @if(!empty($settings['phone']))
                        <li><a href="tel:{{ str_replace(' ', '', $settings['phone']) }}" class="hover:text-vanniyan-gold transition-colors">{{ $settings['phone'] }}</a></li>
                    @endif
                    @if(!empty($settings['phone_secondary']))
                        <li><a href="tel:{{ str_replace(' ', '', $settings['phone_secondary']) }}" class="hover:text-vanniyan-gold transition-colors">{{ $settings['phone_secondary'] }}</a></li>
                    @endif
                    @if(!empty($settings['email']))
                        <li><a href="mailto:{{ $settings['email'] }}" class="hover:text-vanniyan-gold transition-colors">{{ $settings['email'] }}</a></li>
                    @endif
                </ul>

                <h4 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Social</h4>
                <div class="flex space-x-3">
                    @if(!empty($socialInstagram))
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener" aria-label="Instagram" class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center text-gray-300 hover:text-white hover:border-vanniyan-gold hover:bg-vanniyan-gold/10 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialFacebook))
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener" aria-label="Facebook" class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center text-gray-300 hover:text-white hover:border-vanniyan-gold hover:bg-vanniyan-gold/10 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialWhatsapp))
                        <a href="https://wa.me/{{ $socialWhatsapp }}" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center text-gray-300 hover:text-white hover:border-vanniyan-gold hover:bg-vanniyan-gold/10 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    @endif
                    @if(!empty($socialTiktok))
                        <a href="{{ $socialTiktok }}" target="_blank" rel="noopener" aria-label="TikTok" class="w-10 h-10 rounded-full border border-gray-700 flex items-center justify-center text-gray-300 hover:text-white hover:border-vanniyan-gold hover:bg-vanniyan-gold/10 transition-colors duration-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 mb-4 md:mb-0">
                <a href="{{ route('privacy-policy') }}" class="hover:text-gray-300 transition-colors">Privacy Policy</a>
                <a href="{{ route('terms-and-conditions') }}" class="hover:text-gray-300 transition-colors">Terms &amp; Conditions</a>
                <a href="{{ route('sitemap.page') }}" class="hover:text-gray-300 transition-colors">Sitemap</a>
            </div>
            <p>&copy; {{ date('Y') }} Vanniyan Restaurant. All rights reserved.</p>
        </div>

    </div>
</footer>
