<!-- Mobile Navigation Drawer -->
<div 
    id="mobile-navigation-drawer"
    x-show="mobileMenuOpen" 
    class="fixed inset-0 z-50 flex lg:hidden" 
    style="display: none;"
    role="dialog"
    aria-modal="true"
    aria-label="Mobile Navigation"
>
    <!-- Overlay -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/35" 
        @click="mobileMenuOpen = false"
        aria-hidden="true"
    ></div>

    <!-- Sidebar -->
    <div 
        x-show="mobileMenuOpen" 
        x-transition:enter="transition ease-out duration-250 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="relative flex w-full max-w-[360px] flex-col bg-vanniyan-white shadow-xl h-full overflow-y-auto"
        @click.stop
    >
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Vanniyan Restaurant" class="h-10 w-auto">
            </a>
            <button 
                x-ref="closeButton"
                @click="mobileMenuOpen = false" 
                class="flex h-10 w-10 items-center justify-center rounded focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 text-gray-500 hover:text-gray-900"
                aria-label="Close menu"
            >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav aria-label="Mobile primary navigation" class="mt-6 flex-1 px-6 space-y-2">
            @php
                $mobileLinks = [
                    ['name' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
                    ['name' => 'Menu', 'route' => 'menu', 'active' => request()->routeIs('menu')],
                    ['name' => 'Our Deals', 'route' => 'offers', 'active' => request()->routeIs('offers')],
                    ['name' => 'Our Story', 'route' => 'our-story', 'active' => request()->routeIs('our-story', 'our-stories.*')],
                    ['name' => 'Contact', 'route' => 'contact', 'active' => request()->routeIs('contact')],
                ];
            @endphp

            @foreach ($mobileLinks as $link)
                <a 
                    href="{{ route($link['route']) }}" 
                    class="block py-3 text-lg font-serif uppercase tracking-wide transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded
                        {{ $link['active'] ? 'text-vanniyan-green-900 font-bold border-l-2 border-vanniyan-gold pl-3' : 'text-gray-900 hover:text-vanniyan-green-900' }}"
                    {{ $link['active'] ? 'aria-current="page"' : '' }}
                >
                    {{ $link['name'] }}
                </a>
            @endforeach
        </nav>
        
        <!-- Bottom Actions -->
        <div class="mt-auto p-6 bg-gray-50/50 border-t border-gray-100">
            <a 
                href="{{ route('takeaway') }}"
                class="block w-full bg-vanniyan-gold text-white text-center hover:bg-vanniyan-green-900 transition-colors duration-200 font-semibold uppercase tracking-wider text-sm rounded-full h-[48px] leading-[48px] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-gold"
            >
                Order Takeaway
            </a>

            <a 
                href="{{ route('booking.selection') }}"
                class="block w-full bg-vanniyan-green-900 text-white text-center hover:bg-vanniyan-green-800 transition-colors duration-200 font-semibold uppercase tracking-wider text-sm rounded-full h-[48px] leading-[48px] mt-3 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
            >
                Book Now
            </a>
            
            @inject('settingsService', 'App\Services\RestaurantSettingsService')
@php $navPhone = $settingsService->get('phone', ''); @endphp
            <!-- Call Info -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Call Vanniyan</p>
                @if($navPhone)
                <a href="tel:{{ str_replace(' ', '', $navPhone) }}" class="block mt-1 text-vanniyan-green-900 font-medium hover:text-vanniyan-gold transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded inline-block px-2">
                    {{ $navPhone }}
                </a>
                @endif
            </div>
        </div>
        
    </div>
</div>
