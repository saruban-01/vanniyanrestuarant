<x-layouts.app>
    @inject('settingsService', 'App\Services\RestaurantSettingsService')
    <x-analytics.purchase :order="$order" />
    <div class="min-h-screen bg-vanniyan-white py-12 md:py-24">
        <div class="max-w-3xl mx-auto px-6">
            
            <!-- Success Message -->
            <div class="text-center mb-12">
                <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">Your Order is Confirmed</h1>
                <p class="text-gray-600 text-lg">Thank you, {{ $order->customer_name }}. We've received your takeaway order.</p>
            </div>

            <!-- Order Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Header -->
                <div class="bg-vanniyan-green-900 p-6 sm:p-8 text-center border-b-4 border-vanniyan-gold">
                    <span class="text-vanniyan-gold font-bold text-xs tracking-widest uppercase mb-2 block">Order Reference</span>
                    <p class="text-3xl font-mono font-bold text-white tracking-wider">{{ $order->reference }}</p>
                </div>
                
                <div class="p-6 sm:p-8 space-y-8">
                    <!-- Pickup Details -->
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shrink-0 shadow-sm text-vanniyan-green-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1 uppercase tracking-wide text-sm">Pickup Time</h3>
                            <p class="text-lg font-bold text-vanniyan-green-900">{{ $order->pickup_time->format('l, j M - g:i A') }}</p>
                            <p class="text-gray-600 mt-2 text-sm leading-relaxed">
                                Vanniyan Restaurant<br>
                                A9 Road, Kilinochchi
                            </p>
                        </div>
                    </div>

                    <!-- Items Summary -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4 uppercase tracking-wide text-sm border-b pb-2">Order Summary</h3>
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex gap-3">
                                        <span class="font-bold text-vanniyan-green-900 bg-green-50 px-2 py-0.5 rounded text-sm h-fit">{{ $item->quantity }}×</span>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $item->item_name_snapshot }}</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-gray-900 shrink-0">Rs. {{ number_format($item->line_total, 0) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t pt-6 flex justify-between items-center">
                        <span class="text-lg text-gray-900 font-bold">Total to pay at pickup</span>
                        <span class="text-3xl font-bold text-vanniyan-green-900">Rs. {{ number_format($order->total, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ $settingsService->get('maps_url', '#') }}" target="_blank" class="bg-vanniyan-green-900 text-white hover:bg-vanniyan-green-800 transition-colors duration-200 rounded-lg px-8 py-3.5 font-bold text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                    Get Directions
                </a>
                <a href="{{ route('menu') }}" class="border-2 border-vanniyan-green-900 text-vanniyan-green-900 hover:bg-vanniyan-green-900 hover:text-white transition-colors duration-200 rounded-lg px-8 py-3.5 font-bold text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900">
                    Back to Menu
                </a>
            </div>

        </div>
    </div>
</x-layouts.app>
