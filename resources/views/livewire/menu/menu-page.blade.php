<div>
    <!-- Menu Hero -->
    <section class="relative h-[35vh] md:h-[45vh] bg-vanniyan-green-900 flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1544148103-0773bf10d330?auto=format&fit=crop&q=80&w=2000')] bg-cover bg-center opacity-40"></div>
        <div class="absolute inset-0 bg-vanniyan-green-900/50 backdrop-blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-vanniyan-green-900 via-vanniyan-green-900/60 to-vanniyan-green-900/30"></div>
        <div class="relative z-10 text-center px-6">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm mb-4 block">Vanniyan Restaurant</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">Our Menu</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-6"></div>
            <p class="text-gray-200 text-lg md:text-xl font-light max-w-lg mx-auto">
                Discover the flavours of Vanni, prepared for the table or takeaway.
            </p>
        </div>
    </section>

    <!-- Main Menu Area -->
    <div class="bg-vanniyan-white min-h-screen relative">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 py-8 flex flex-col lg:flex-row gap-8">
            
            <!-- Left/Main Content -->
            <div class="flex-1 transition-all duration-300 min-w-0">
                
                <!-- Mode Switcher -->
                <div class="flex justify-center mb-10">
                    <div class="inline-flex bg-white rounded-lg p-1 border border-vanniyan-border shadow-sm">
                        <button 
                            wire:click="setMode('dinein')"
                            class="px-8 py-2.5 rounded-md font-bold text-sm transition-colors duration-200 {{ $mode === 'dinein' ? 'bg-vanniyan-green-900 text-white' : 'text-vanniyan-green-900 hover:bg-gray-50' }}"
                        >
                            DINE-IN
                        </button>
                        <button 
                            wire:click="setMode('takeaway')"
                            class="px-8 py-2.5 rounded-md font-bold text-sm transition-colors duration-200 {{ $mode === 'takeaway' ? 'bg-vanniyan-green-900 text-white' : 'text-vanniyan-green-900 hover:bg-gray-50' }}"
                        >
                            TAKEAWAY
                        </button>
                    </div>
                </div>

                <!-- Takeaway Status Indicator -->
                @if($mode === 'takeaway')
                <div class="text-center mb-10">
                    @if($isOpen)
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-bold tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        TAKEAWAY OPEN
                    </span>
                    <p class="text-sm text-gray-600 font-medium">Pickup orders are currently available.</p>
                    @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-bold tracking-wider mb-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                        TAKEAWAY CLOSED
                    </span>
                    <p class="text-sm text-gray-600 font-medium">
                        We're currently closed. Pickup ordering will be available again
                        @if($nextOpening) {{ $nextOpening }}.@else during our opening hours.@endif
                    </p>
                    @endif
                </div>
                @endif

                <!-- Search -->
                <div class="max-w-2xl mx-auto mb-10 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        placeholder="Search our menu..."
                        class="w-full pl-11 pr-4 py-3 bg-white border border-vanniyan-border rounded-xl focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 focus:border-transparent text-vanniyan-text shadow-sm"
                    >
                </div>

                <!-- Category Cards -->
                @if(!$search)
                <div class="mb-8 text-center">
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900">Explore by Category</h2>
                    <div class="w-12 h-px bg-vanniyan-gold mx-auto mt-4"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach($categories as $cat)
                        @php
                            $coverImage = $cat->items->first()?->image_url ?? 'https://via.placeholder.com/800x500?text=Vanniyan';
                        @endphp
                        <div wire:key="cat-{{ $cat->id }}" class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl hover:border-vanniyan-gold/30 transition-all duration-500 hover:-translate-y-1.5 flex flex-col">
                            <div class="relative h-52 overflow-hidden bg-gray-100">
                                <img
                                    src="{{ $coverImage }}"
                                    alt="{{ $cat->name }}"
                                    class="absolute inset-0 w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-vanniyan-green-900/85 via-vanniyan-green-900/25 to-transparent"></div>
                                <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-vanniyan-green-900 text-[11px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                    {{ $cat->items->count() }} item{{ $cat->items->count() !== 1 ? 's' : '' }}
                                </span>
                                <div class="absolute bottom-5 left-5 right-5">
                                    <h3 class="text-2xl font-serif font-bold text-white mb-2">{{ $cat->name }}</h3>
                                    <div class="w-10 h-[2px] bg-vanniyan-gold"></div>
                                </div>
                            </div>
                            <div class="p-5 flex items-center justify-between gap-4">
                                <p class="text-sm text-gray-500 font-medium leading-snug">
                                    {{ $cat->items->first()?->description ? \Illuminate\Support\Str::limit($cat->items->first()->description, 60) : 'Discover the flavours of ' . $cat->name . '.' }}
                                </p>
                                <button wire:click="openCategory({{ $cat->id }})" class="shrink-0 inline-flex items-center gap-2 bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 font-bold text-sm uppercase tracking-wider rounded-full px-6 py-2.5 shadow-sm hover:shadow-md">
                                    View
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <!-- Search Results -->
                @if($search && $items->isNotEmpty())
                <div>
                    <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6">Search Results</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                        @foreach($items as $item)
                            @include('livewire.menu.partials.food-card', ['item' => $item, 'isOpen' => $isOpen])
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Sidebar: Cart -->
            @if($mode === 'takeaway')
            <div class="w-full lg:w-80 xl:w-96 shrink-0 relative">
                <div class="sticky top-24">
                    @if($isOpen)
                    <livewire:takeaway.cart-sidebar />
                    @else
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 text-center">
                        <div class="w-16 h-16 mx-auto bg-red-50 rounded-full flex items-center justify-center mb-5">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Takeaway Closed</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            We're currently closed.
                            @if($nextOpening) Ordering reopens {{ $nextOpening }}.@else Please check back during our opening hours.@endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    <livewire:menu.category-modal />
    <livewire:takeaway.checkout-flow />
</div>
