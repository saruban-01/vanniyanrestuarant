<div wire:key="menu-item-{{ $item->id }}" class="bg-white border border-vanniyan-border rounded-xl overflow-hidden group hover:shadow-lg transition-shadow duration-300 flex flex-col h-full relative">
    
    <!-- Labels -->
    @if($item->is_signature)
    <div class="absolute top-4 left-4 z-10 flex flex-col gap-2">
        <span class="bg-vanniyan-gold text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded shadow-sm">Signature</span>
    </div>
    @endif

    <!-- Image -->
    <div class="relative h-48 md:h-56 overflow-hidden bg-gray-100">
        <img 
            src="{{ $item->image_url ? str_replace(['w=800', 'q=80'], ['w=400', 'q=70'], $item->image_url) : asset('images/placeholder.svg') }}" 
            alt="{{ $item->name }}" 
            class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
        >
    </div>
    
    <!-- Content -->
    <div class="p-6 flex flex-col flex-1">
        <h3 class="text-xl font-poppins font-bold text-vanniyan-green-900 mb-2">
            {{ $item->name }}
        </h3>
        
        @if($item->description)
        <p class="text-gray-600 text-sm mb-6 line-clamp-2">
            {{ $item->description }}
        </p>
        @endif
        
        <div class="mt-auto flex items-center justify-between">
            <span class="text-lg font-bold text-vanniyan-green-900">
                Rs. {{ number_format($item->price, 0) }}
            </span>
            
            @if($mode === 'takeaway')
                @if(isset($isOpen) && !$isOpen)
                    <button 
                        disabled
                        class="bg-gray-200 text-gray-400 cursor-not-allowed rounded-lg px-6 py-2.5 font-bold text-sm"
                    >
                        CLOSED
                    </button>
                @else
                    @php $count = $cartCounts[$item->id] ?? 0; @endphp
                    @if($count > 0)
                    <div class="flex items-center gap-1 bg-vanniyan-green-900 text-white rounded-lg p-1">
                        <button wire:click="decrement({{ $item->id }})" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-vanniyan-green-800 transition-colors font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-gold" aria-label="Remove one {{ $item->name }} from order">
                            −
                        </button>
                        <span class="w-7 text-center font-bold" aria-live="polite">{{ $count }}</span>
                        <button wire:click="quickAdd({{ $item->id }})" class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-vanniyan-green-800 transition-colors font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-gold" aria-label="Add another {{ $item->name }}">
                            +
                        </button>
                    </div>
                    @else
                    <button 
                        wire:click="quickAdd({{ $item->id }})"
                        class="bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 rounded-lg px-6 py-2.5 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
                    >
                        + ADD
                    </button>
                    @endif
                @endif
            @endif
        </div>
    </div>
</div>
