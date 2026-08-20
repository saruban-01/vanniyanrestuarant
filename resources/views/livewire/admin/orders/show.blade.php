<div>
    <div class="mb-6">
        <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-gray-500 uppercase tracking-wider hover:text-vanniyan-green-900 transition-colors">&larr; Back to Orders</a>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 uppercase tracking-widest">{{ $order->reference }}</h1>
                @php
                    $statusColors = [
                        'received' => 'bg-red-100 text-red-800 border-red-200',
                        'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-gray-100 text-gray-800 border-gray-200',
                    ];
                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $color }}">
                    {{ $order->status }}
                </span>
            </div>
            <p class="text-gray-500 text-sm">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm transition-colors">
                Print Ticket
            </button>
            @if(!in_array($order->status, ['completed', 'cancelled']))
                <button wire:click="$set('showCancelModal', true)" class="px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 rounded text-xs font-bold text-red-700 uppercase tracking-wider shadow-sm transition-colors">
                    Cancel Order
                </button>
            @endif
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-12">
        
        <!-- Left Column: Order Items & Status -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Status Timeline -->
            @if(!in_array($order->status, ['cancelled']))
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Status Progression</h2>
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
                    @php
                        $currentIndex = array_search($order->status, $timelineStatuses);
                    @endphp
                    @foreach($timelineStatuses as $index => $status)
                        @php
                            $isCompleted = $index <= $currentIndex;
                            $isCurrent = $index === $currentIndex;
                            $canAdvance = $index === $currentIndex + 1;
                        @endphp
                        <div class="relative z-10 flex flex-col items-center gap-2">
                            @if($canAdvance)
                                <button wire:click="updateStatus('{{ $status }}')" class="w-8 h-8 rounded-full flex items-center justify-center border-2 border-vanniyan-green-900 bg-white text-vanniyan-green-900 hover:bg-vanniyan-green-900 hover:text-white transition-colors cursor-pointer shadow-sm group">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    <div class="absolute -top-10 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider whitespace-nowrap">Mark as {{ $status }}</div>
                                </button>
                            @else
                                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $isCompleted ? 'bg-vanniyan-green-900 border-vanniyan-green-900 text-white' : 'bg-gray-100 border-gray-200 text-gray-300' }}">
                                    @if($isCompleted)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-gray-300"></div>
                                    @endif
                                </div>
                            @endif
                            <div class="text-[10px] font-bold uppercase tracking-wider {{ $isCurrent ? 'text-vanniyan-green-900' : ($isCompleted ? 'text-gray-700' : 'text-gray-400') }}">{{ $status }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Order Items -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Order Items</h2>
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ $order->items->sum('quantity') }} Items</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                        <div class="p-6 flex justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-sm font-bold text-gray-700 flex-shrink-0">
                                    {{ $item->quantity }}x
                                </div>
                                <div>
                                    <div class="font-bold text-gray-900">{{ $item->item_name_snapshot }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900">Rs. {{ number_format($item->line_total, 2) }}</div>
                                @if($item->quantity > 1)
                                    <div class="text-xs text-gray-400 mt-1">Rs. {{ number_format($item->unit_price_snapshot, 2) }} each</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-2">
                        <div class="text-sm text-gray-500 font-medium">Subtotal</div>
                        <div class="text-sm font-medium text-gray-900">Rs. {{ number_format($order->subtotal, 2) }}</div>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="text-base font-bold text-gray-900 uppercase tracking-wider">Total</div>
                        <div class="text-xl font-serif font-bold text-vanniyan-green-900">Rs. {{ number_format($order->total, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Notes -->
            @if($order->order_note)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xs font-bold text-yellow-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    Customer Note
                </h2>
                <p class="text-yellow-900 text-sm whitespace-pre-wrap">{{ $order->order_note }}</p>
            </div>
            @endif

            @if($order->status === 'cancelled' && $order->cancellation_reason)
            <div class="bg-red-50 border border-red-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xs font-bold text-red-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                    Cancellation Reason
                </h2>
                <p class="text-red-900 text-sm whitespace-pre-wrap">{{ $order->cancellation_reason }}</p>
            </div>
            @endif

        </div>

        <!-- Right Column: Customer & Pickup Info -->
        <div class="space-y-6">
            
            <!-- Pickup Info -->
            <div class="bg-vanniyan-green-900 text-white rounded-xl shadow-sm p-6">
                <h2 class="text-[10px] text-vanniyan-gold font-bold uppercase tracking-widest mb-4">Pickup Target</h2>
                <div class="text-3xl font-serif font-bold mb-1">{{ $order->pickup_time->format('h:i A') }}</div>
                <div class="text-sm text-gray-300">{{ $order->pickup_time->format('l, d F Y') }}</div>
            </div>

            <!-- Customer Details -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Customer</h2>
                <div class="space-y-4">
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Name</div>
                        <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Phone</div>
                        <div class="font-bold text-gray-900">
                            <a href="tel:{{ $order->customer_phone }}" class="hover:text-vanniyan-green-900 transition-colors">{{ $order->customer_phone }}</a>
                        </div>
                    </div>
                    @if($order->customer_email)
                    <div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Email</div>
                        <div class="font-medium text-gray-700">
                            <a href="mailto:{{ $order->customer_email }}" class="hover:text-vanniyan-green-900 transition-colors">{{ $order->customer_email }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Note -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Internal Admin Note</h2>
                <textarea wire:model="adminNote" rows="3" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-vanniyan-green-900 focus:ring-vanniyan-green-900 mb-3" placeholder="Add a private note (not visible to customer)..."></textarea>
                <button wire:click="saveAdminNote" class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-bold uppercase tracking-wider transition-colors">Save Note</button>
            </div>

        </div>
    </div>

    <!-- Cancellation Modal -->
    @if($showCancelModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-xl font-serif font-bold text-gray-900 mb-2">Cancel Order</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to cancel order <strong>{{ $order->reference }}</strong>? This action cannot be undone.</p>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cancellation Reason <span class="text-red-500">*</span></label>
                <input type="text" wire:model="cancellationReason" placeholder="e.g. Items out of stock, customer requested" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                @error('cancellationReason') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-3">
                <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded-md text-sm font-bold uppercase tracking-wider hover:bg-gray-50">Keep Order</button>
                <button wire:click="cancelOrder" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm font-bold uppercase tracking-wider hover:bg-red-700">Confirm Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
