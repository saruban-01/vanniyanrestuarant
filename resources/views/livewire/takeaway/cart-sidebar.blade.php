<div class="bg-white rounded-2xl shadow-lg border border-gray-100 flex flex-col h-[calc(100vh-8rem)] font-poppins">
    <!-- Header -->
    <div class="p-6 border-b border-gray-100 shrink-0">
        <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest block mb-1">Your Order</span>
        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">YOUR ORDER</h2>
        <div class="w-8 h-[2px] bg-vanniyan-gold mb-3"></div>
        <p class="text-sm text-gray-500 mt-1 font-medium">{{ count($displayCart) }} ITEM{{ count($displayCart) !== 1 ? 'S' : '' }}</p>
    </div>

    <!-- Empty State -->
    @if(empty($displayCart))
    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Your order is empty</h3>
        <p class="text-gray-500">Choose something delicious from the menu.</p>
    </div>
    @else
    <!-- Cart Items -->
    <div class="flex-1 overflow-y-auto p-6 space-y-6">
        @foreach($displayCart as $item)
        <div class="flex gap-4 items-start">
            <!-- Details -->
            <div class="flex-1 min-w-0">
                <h4 class="font-poppins font-bold text-gray-900 truncate pr-4 mb-1">{{ $item['menu_item']->name }}</h4>
                <span class="font-bold text-gray-900">Rs. {{ number_format($item['line_total'], 0) }}</span>
                
                <button wire:click="removeItem('{{ $item['id'] }}')" class="block mt-2 text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider transition-colors focus:outline-none focus:ring-2 focus:ring-red-500 rounded">
                    Remove
                </button>
            </div>
            
            <!-- Qty stepper on the right -->
            <div class="shrink-0">
                <div class="inline-flex items-center gap-1 bg-gray-100 rounded-full p-1">
                    <button wire:click="decrementItem({{ $item['menu_item']->id }})" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:shadow-sm transition-all text-gray-600 font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900" aria-label="Remove one {{ $item['menu_item']->name }} from order">
                        −
                    </button>
                    <span class="w-7 text-center font-bold text-vanniyan-green-900" aria-live="polite">{{ $item['quantity'] }}</span>
                    <button wire:click="increment({{ $item['menu_item']->id }})" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white hover:shadow-sm transition-all text-gray-600 font-bold focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900" aria-label="Add another {{ $item['menu_item']->name }}">
                        +
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Footer -->
    <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0 rounded-b-xl">
        <div class="flex justify-between items-center mb-6">
            <span class="text-gray-600 font-medium">Subtotal</span>
            <span class="text-xl font-bold text-gray-900">Rs. {{ number_format($subtotal, 0) }}</span>
        </div>
        
        <button 
            wire:click="checkout"
            class="w-full bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 rounded-full px-6 py-4 font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900 shadow-md"
        >
            VIEW ORDER
        </button>
    </div>
    @endif
</div>
