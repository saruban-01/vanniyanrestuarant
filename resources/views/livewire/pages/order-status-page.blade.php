<div class="bg-surface min-h-screen pt-12 font-sans" wire:poll.15s="refreshStatus">
    
    <!-- SEO Protection -->
    <x-slot name="head">
        <meta name="robots" content="noindex,nofollow">
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(!$order)
            <!-- Unauthorized / Not Found State -->
            <div class="mt-20">
                <x-site.empty-state 
                    title="Order Access Unavailable" 
                    description="Please use the secure order status link from your confirmation page or contact Vanniyan if you need assistance."
                    icon="search"
                />
            </div>
        @else
            
            <!-- Hero -->
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-widest mb-4 block">Takeaway Order</span>
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">
                    <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-6"></div>
                    {{ strtoupper($statusService->getStatusHeading($order->status)) }}
                </h1>
                <p class="text-gray-600 text-lg max-w-xl mx-auto">
                    {{ $statusService->getStatusDescription($order->status) }}
                </p>
                <div class="mt-6 inline-flex items-center space-x-2 bg-white px-4 py-2 border border-gray-100 rounded-2xl">
                    <span class="text-sm font-bold text-gray-800 tracking-wider">ORDER #{{ $order->reference }}</span>
                    <button 
                        x-data="{ copied: false }" 
                        @click="navigator.clipboard.writeText('{{ $order->reference }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="text-gray-400 hover:text-vanniyan-green-900 focus:outline-none transition-colors"
                        title="Copy Order Number"
                    >
                        <span x-show="copied" class="text-xs font-bold text-vanniyan-green-900 uppercase tracking-wider ml-2">Copied</span>
                        <svg x-show="!copied" class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
            </div>

            @if($order->status !== App\Services\OrderStatusService::STATUS_CANCELLED)
                <!-- Timeline -->
                <div class="bg-white border border-gray-100 rounded-2xl px-6 py-8 mb-8 shadow-sm">
                    <div class="flex flex-col md:flex-row justify-between relative">
                        @php
                            $timeline = $statusService->getTimelineStatuses();
                            $currentIndex = array_search($order->status, $timeline);
                            if ($currentIndex === false && $order->status === App\Services\OrderStatusService::STATUS_COMPLETED) {
                                $currentIndex = 4;
                            }
                        @endphp
                        
                        <!-- Desktop Line -->
                        <div class="hidden md:block absolute top-4 left-6 right-6 h-0.5 bg-gray-100 z-0"></div>
                        <!-- Mobile Line -->
                        <div class="block md:hidden absolute left-4 top-6 bottom-6 w-0.5 bg-gray-100 z-0"></div>

                        @foreach($timeline as $index => $statusStep)
                            @php
                                $isCompleted = $index < $currentIndex;
                                $isCurrent = $index === $currentIndex;
                                $isFuture = $index > $currentIndex;
                                
                                $labels = [
                                    'received' => 'Received',
                                    'confirmed' => 'Confirmed',
                                    'completed' => 'Completed'
                                ];
                                $label = $labels[$statusStep] ?? ucfirst($statusStep);
                            @endphp
                            
                            <div class="relative z-10 flex md:flex-col items-center mb-8 md:mb-0 last:mb-0">
                                <!-- Node -->
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 bg-white flex-shrink-0
                                    {{ $isCompleted ? 'border-vanniyan-green-900 bg-vanniyan-green-900 text-white' : '' }}
                                    {{ $isCurrent ? 'border-vanniyan-gold text-vanniyan-gold' : '' }}
                                    {{ $isFuture ? 'border-gray-200 text-gray-300' : '' }}
                                ">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif($isCurrent)
                                        <div class="w-2.5 h-2.5 rounded-full bg-vanniyan-gold"></div>
                                    @endif
                                </div>
                                <!-- Label -->
                                <div class="ml-4 md:ml-0 md:mt-4 text-left md:text-center">
                                    <span class="block text-xs font-bold uppercase tracking-wider {{ $isCurrent ? 'text-vanniyan-green-900' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') }}">
                                        {{ $label }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Pickup & Contact Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Pickup Info -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif font-bold text-xl text-vanniyan-green-900 mb-6">Pickup</h3>
                        
                        <div class="mb-4">
                            <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Date</span>
                            <span class="block text-gray-900 font-medium">{{ $order->pickup_time->format('d F Y') }}</span>
                        </div>
                        
                        <div class="mb-6">
                            <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Time</span>
                            <span class="block text-gray-900 font-medium">{{ $order->pickup_time->format('h:i A') }}</span>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <span class="block text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Location</span>
                            <span class="block font-bold text-vanniyan-green-900 mb-1">{{ $restaurantName }}</span>
                            <span class="block text-sm text-gray-600 mb-4">{{ $restaurantAddress }}</span>
                            
                            @if($order->status !== App\Services\OrderStatusService::STATUS_COMPLETED)
                                <a href="{{ $restaurantMapsUrl }}" target="_blank" class="text-sm font-bold text-vanniyan-gold hover:text-yellow-600 transition-colors uppercase tracking-wider flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Get Directions
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif font-bold text-lg text-vanniyan-green-900 mb-2">Need Help?</h3>
                        <p class="text-sm text-gray-600 mb-6">Have a question about this takeaway order? Contact Vanniyan.</p>
                        <a href="tel:{{ str_replace(' ', '', $restaurantPhone) }}" class="block w-full text-center px-4 py-2 border border-vanniyan-green-900 text-vanniyan-green-900 font-bold uppercase tracking-wider text-xs rounded-full hover:bg-vanniyan-green-50 transition-colors">
                            Call {{ $restaurantPhone }}
                        </a>
                    </div>
                </div>

                <!-- Order Summary Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-serif font-bold text-xl text-vanniyan-green-900 mb-6">Your Order</h3>
                        
                        <div class="space-y-4 mb-6">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-start pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div>
                                        <div class="flex items-baseline">
                                            <span class="font-bold text-gray-900 mr-2">{{ $item->quantity }} ×</span>
                                            <span class="font-medium text-gray-900">{{ $item->item_name_snapshot }}</span>
                                        </div>
                                    </div>
                                    <div class="font-medium text-gray-900 whitespace-nowrap ml-4">
                                        Rs. {{ number_format($item->line_total, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <div class="flex justify-between items-center text-lg">
                                <span class="font-serif font-bold text-vanniyan-green-900 uppercase tracking-widest">Total</span>
                                <span class="font-bold text-vanniyan-green-900">Rs. {{ number_format($order->total, 2) }}</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-right">Order for: <span class="font-bold text-gray-700">{{ $order->customer_name }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->status === App\Services\OrderStatusService::STATUS_COMPLETED)
                <div class="mt-12 text-center">
                    <h3 class="font-serif font-bold text-2xl text-vanniyan-green-900 mb-6">Thank You For Choosing Vanniyan</h3>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('menu') }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded-full hover:bg-gray-50 transition-colors">View Menu</a>
                        <a href="{{ route('reservation') }}" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-vanniyan-green-800 transition-colors">Reserve a Table</a>
                    </div>
                </div>
            @endif

        @endif
        
    </div>
</div>
