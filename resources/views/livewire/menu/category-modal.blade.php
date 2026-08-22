<div>
    @if($isOpen && $category)
    <div class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity"
         x-data
         x-on:keydown.escape.window="$wire.close()"
         style="display: none;"
         x-show="$wire.isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="bg-white w-full sm:w-[640px] sm:rounded-2xl rounded-t-3xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl relative"
             @click.outside="$wire.close()"
             x-show="$wire.isOpen"
             x-transition:enter="ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-8 sm:scale-95"
             x-transition:enter-end="translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100"
             x-transition:leave-end="translate-y-full sm:translate-y-8 sm:scale-95">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between shrink-0 bg-white">
                <div>
                    <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900">{{ $category->name }}</h2>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mt-1">{{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}</p>
                </div>
                <button wire:click="close" class="p-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Items -->
            <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50/60">
                @forelse($items as $item)
                <div wire:key="cat-item-{{ $item->id }}" class="flex gap-4 p-4 bg-white border border-gray-100 rounded-2xl hover:border-vanniyan-gold/30 hover:shadow-lg transition-all duration-300">
                    <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                        <img src="{{ $item->image_url ? str_replace(['w=800', 'q=80'], ['w=400', 'q=70'], $item->image_url) : asset('images/placeholder.svg') }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col">
                        <h3 class="font-poppins font-bold text-vanniyan-green-900 text-lg">{{ $item->name }}</h3>
                        @if($item->description)
                        <p class="text-sm text-gray-500 line-clamp-2 mt-1">{{ $item->description }}</p>
                        @endif
                        <p class="text-vanniyan-green-900 font-bold mt-auto pt-2">Rs. {{ number_format($item->price, 0) }}</p>
                    </div>
                    <div class="shrink-0 self-center">
                        @if($mode === 'takeaway' && $restaurantOpen)
                        @php $count = $cartCounts[$item->id] ?? 0; @endphp
                        @if($count > 0)
                        <div class="inline-flex items-center gap-1 bg-vanniyan-green-900 text-white rounded-full p-1 shadow-sm">
                            <button wire:click="decrement({{ $item->id }})" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-vanniyan-green-800 transition-colors font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-gold" aria-label="Remove one {{ $item->name }} from order">
                                −
                            </button>
                            <span class="w-7 text-center font-bold" aria-live="polite">{{ $count }}</span>
                            <button wire:click="quickAdd({{ $item->id }})" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-vanniyan-green-800 transition-colors font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-gold" aria-label="Add another {{ $item->name }}">
                                +
                            </button>
                        </div>
                        @else
                        <button wire:click="quickAdd({{ $item->id }})" class="inline-flex items-center gap-2 bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-300 rounded-full px-5 py-2.5 text-xs font-bold uppercase tracking-wider shadow-sm hover:shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Add to Cart
                        </button>
                        @endif
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No items in this category yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif
</div>