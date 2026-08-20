<div>
    @if($isOpen)
    <div class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 sm:p-6"
         x-data
         x-on:keydown.escape.window="$wire.close()"
         style="display: none;"
         x-show="$wire.isOpen">
         
        <div class="bg-white w-full max-w-2xl rounded-2xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl relative"
             @click.outside="$wire.close()">
             
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0 bg-gray-50">
                <div class="flex items-center gap-4">
                    @if($step > 1)
                    <button wire:click="prevStep" class="p-2 -ml-2 rounded-lg hover:bg-gray-200 text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    @endif
                    <h2 class="text-xl font-bold text-gray-900">
                        @if($step == 1) 01. Order Review @endif
                        @if($step == 2) 02. Choose Pickup @endif
                        @if($step == 3) 03. Your Details @endif
                        @if($step == 4) 04. Final Review @endif
                    </h2>
                </div>
                <button wire:click="close" class="p-2 rounded-lg hover:bg-gray-200 text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6 md:p-8">
                @if(session()->has('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-start">
                        <svg class="w-5 h-5 mr-3 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- STEP 1: REVIEW -->
                @if($step === 1)
                    <div class="space-y-6">
                        @foreach($displayCart as $item)
                        <div class="flex gap-4 p-4 border rounded-xl border-gray-100 bg-gray-50/50">
                            <div class="shrink-0 w-8 h-8 bg-white border border-gray-200 text-vanniyan-green-900 rounded font-bold flex items-center justify-center text-sm shadow-sm">
                                {{ $item['quantity'] }}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-900">{{ $item['menu_item']->name }}</h4>
                                    <span class="font-bold text-gray-900">Rs. {{ number_format($item['line_total'], 0) }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="border-t pt-4 flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Subtotal</span>
                            <span class="text-2xl font-bold text-gray-900">Rs. {{ number_format($subtotal, 0) }}</span>
                        </div>
                    </div>
                @endif

                <!-- STEP 2: PICKUP -->
                @if($step === 2)
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">When would you like to collect?</h3>
                        @error('selectedPickupTime') <span class="text-red-500 text-sm font-medium mb-4 block">{{ $message }}</span> @enderror
                        
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($pickupSlots as $slot)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="selectedPickupTime" value="{{ $slot['value'] }}" class="peer sr-only">
                                    <div class="text-center p-4 rounded-xl border-2 font-bold transition-all peer-checked:border-vanniyan-green-900 peer-checked:bg-green-50 peer-checked:text-vanniyan-green-900 border-gray-200 text-gray-600 hover:border-gray-300">
                                        {{ $slot['label'] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- STEP 3: DETAILS -->
                @if($step === 3)
                    <div>
                        <div class="mb-7 text-center">
                            <div class="w-14 h-14 mx-auto mb-3 bg-vanniyan-green-900/5 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-vanniyan-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="font-poppins font-bold text-2xl text-vanniyan-green-900">Your Details</h3>
                            <p class="text-sm text-gray-500 mt-1.5">Tell us who's collecting the order so we can serve you faster.</p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label for="customerName" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        Full Name
                                    </span>
                                    <span class="text-vanniyan-gold font-bold">Required</span>
                                </label>
                                <input id="customerName" type="text" wire:model="customerName" placeholder="e.g. Thikshan Arulampalam"
                                    class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3.5 font-medium text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-vanniyan-green-900 focus:bg-white focus:outline-none focus:ring-4 focus:ring-vanniyan-green-900/10 @error('customerName') border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-400/10 @enderror">
                                @error('customerName') <span class="text-red-500 text-xs font-bold mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="customerPhone" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        Mobile Number
                                    </span>
                                    <span class="text-vanniyan-gold font-bold">Required</span>
                                </label>
                                <input id="customerPhone" type="tel" wire:model="customerPhone" placeholder="07XXXXXXXX"
                                    class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3.5 font-medium text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-vanniyan-green-900 focus:bg-white focus:outline-none focus:ring-4 focus:ring-vanniyan-green-900/10 @error('customerPhone') border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-400/10 @enderror">
                                @error('customerPhone') <span class="text-red-500 text-xs font-bold mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="customerEmail" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        Email
                                    </span>
                                    <span class="text-gray-400 font-semibold normal-case tracking-normal">Optional</span>
                                </label>
                                <input id="customerEmail" type="email" wire:model="customerEmail" placeholder="you@example.com"
                                    class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3.5 font-medium text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-vanniyan-green-900 focus:bg-white focus:outline-none focus:ring-4 focus:ring-vanniyan-green-900/10 @error('customerEmail') border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-400/10 @enderror">
                                @error('customerEmail') <span class="text-red-500 text-xs font-bold mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="orderNote" class="flex items-center justify-between text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-vanniyan-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Order Notes
                                    </span>
                                    <span class="text-gray-400 font-semibold normal-case tracking-normal">Optional</span>
                                </label>
                                <textarea id="orderNote" wire:model="orderNote" rows="3" placeholder="Anything we should know? e.g. extra cutlery, less spicy..." class="w-full rounded-xl border-2 border-gray-100 bg-gray-50 px-4 py-3.5 font-medium text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-vanniyan-green-900 focus:bg-white focus:outline-none focus:ring-4 focus:ring-vanniyan-green-900/10 resize-none @error('orderNote') border-red-300 bg-red-50 focus:border-red-400 focus:ring-red-400/10 @enderror"></textarea>
                                @error('orderNote') <span class="text-red-500 text-xs font-bold mt-1.5 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- STEP 4: FINAL REVIEW -->
                @if($step === 4)
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Pickup Details</h3>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                <svg class="w-6 h-6 text-vanniyan-green-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($selectedPickupTime)->format('l, j M - g:i A') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Vanniyan Restaurant, A9 Road</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Customer</h3>
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="font-bold text-gray-900">{{ $customerName }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $customerPhone }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-6 flex justify-between items-center">
                            <span class="text-lg text-gray-900 font-bold">Total to pay at pickup</span>
                            <span class="text-3xl font-bold text-vanniyan-green-900">Rs. {{ number_format($subtotal, 0) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer Buttons -->
            <div class="p-6 border-t border-gray-100 bg-white shrink-0">
                @if($step < 4)
                    <button wire:click="nextStep" class="w-full bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 rounded-full px-6 py-4 font-bold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                        CONTINUE
                    </button>
                @else
                    <button 
                        wire:click="placeOrder" 
                        wire:loading.attr="disabled"
                        class="w-full bg-vanniyan-gold text-white hover:bg-yellow-600 transition-colors duration-200 rounded-full px-6 py-4 font-bold text-lg flex justify-center items-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-gold disabled:opacity-70"
                    >
                        <span wire:loading.remove>PLACE TAKEAWAY ORDER</span>
                        <span wire:loading>Processing...</span>
                    </button>
                    <p class="text-xs text-gray-500 mt-3 font-medium text-center">By placing this order, you agree to our <a href="{{ route('terms-and-conditions') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Terms &amp; Conditions</a> and acknowledge our <a href="{{ route('privacy-policy') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Privacy Policy</a>.</p>
                @endif
            </div>

        </div>
    </div>
    @endif
</div>
