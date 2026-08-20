<header 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false 
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 10)"
    @keydown.escape.window="mobileMenuOpen = false"
    x-init="$watch('mobileMenuOpen', value => {
        if (value) {
            document.body.style.overflow = 'hidden';
            setTimeout(() => $refs.closeButton.focus(), 100);
        } else {
            document.body.style.overflow = '';
            $refs.menuButton.focus();
        }
    })"
    :class="{ 'shadow-[0_4px_16px_rgba(0,0,0,0.05)]': scrolled }"
    class="bg-vanniyan-white border-b border-gray-100 py-4 sticky top-0 z-40 transition-shadow duration-200"
>
    <div class="max-w-[1280px] mx-auto px-6 md:px-12 flex justify-between items-center h-[40px] md:h-[52px]">
        
        <!-- Logo -->
        <a href="{{ route('home') }}" class="flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded" aria-label="Vanniyan Home">
            <img src="{{ asset('images/logo.png') }}" alt="Vanniyan Restaurant" class="h-10 md:h-12 w-auto">
        </a>
        
        <!-- Desktop Navigation -->
        <nav aria-label="Primary navigation" class="hidden lg:flex space-x-8 items-center h-full">
            @php
                $navLinks = [
                    ['name' => 'Home', 'route' => 'home', 'active' => request()->routeIs('home')],
                    ['name' => 'Menu', 'route' => 'menu', 'active' => request()->routeIs('menu')],
                    ['name' => 'Our Deals', 'route' => 'offers', 'active' => request()->routeIs('offers')],
                    ['name' => 'Our Story', 'route' => 'our-story', 'active' => request()->routeIs('our-story', 'our-stories.*')],
                    ['name' => 'Contact', 'route' => 'contact', 'active' => request()->routeIs('contact')],
                ];
            @endphp

            @foreach ($navLinks as $link)
                <a 
                    href="{{ route($link['route']) }}" 
                    class="relative text-[13px] font-semibold uppercase tracking-wider transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded px-1 py-1
                        {{ $link['active'] ? 'text-vanniyan-green-900' : 'text-gray-700 hover:text-vanniyan-green-900 group' }}"
                    {{ $link['active'] ? 'aria-current="page"' : '' }}
                >
                    {{ $link['name'] }}
                    
                    <!-- Active/Hover Gold Underline -->
                    <span class="absolute left-0 -bottom-1 h-[2px] bg-vanniyan-gold transition-all duration-200 
                        {{ $link['active'] ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            @endforeach
        </nav>
        
        <!-- Desktop Actions -->
        <div class="hidden lg:flex items-center space-x-6">
            <!-- Cart -->
            @php
                $cartCount = count(Session::get('takeaway_cart', []));
            @endphp
            <a 
                href="{{ route('takeaway') }}" 
                class="flex items-center text-gray-700 hover:text-vanniyan-green-900 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded p-1"
                aria-label="Takeaway cart, {{ $cartCount }} items"
            >
                <span class="relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-vanniyan-gold text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full leading-none">
                            {{ $cartCount }}
                        </span>
                    @endif
                </span>
            </a>
            
            <!-- Reservation CTA -->
            <a 
                href="{{ route('booking.selection') }}"
                class="bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 font-semibold uppercase tracking-wider text-[13px] rounded-full px-6 py-2.5 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
            >
                Book Now
            </a>
        </div>
        
        <!-- Mobile Actions -->
        <div class="flex lg:hidden items-center space-x-5">
            <!-- Mobile Cart -->
            <a 
                href="{{ route('takeaway') }}" 
                class="text-gray-700 hover:text-vanniyan-green-900 relative focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded p-1"
                aria-label="Takeaway cart, {{ $cartCount }} items"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-1 -right-1 bg-vanniyan-gold text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full leading-none">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            
            <!-- Mobile Menu Toggle -->
            <button 
                x-ref="menuButton"
                @click="mobileMenuOpen = true" 
                class="text-gray-900 hover:text-vanniyan-green-900 focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 rounded p-1"
                aria-label="Open menu"
                :aria-expanded="mobileMenuOpen.toString()"
                aria-controls="mobile-navigation-drawer"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

    </div>

    <!-- Mobile Navigation Drawer -->
    <x-mobile-nav />
</header>
