<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Daily Operations</h1>
            <p class="text-gray-500 text-sm">Real-time control center for today's service ({{ \Carbon\Carbon::now()->format('D, d M Y') }}).</p>
        </div>
        
        <div class="flex items-center gap-4">
            <!-- Service Switches -->
            <div class="flex items-center gap-4 bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                <button wire:click="toggleTakeaway" class="relative inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition-colors {{ $takeawayPaused ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $takeawayPaused ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    {{ $takeawayPaused ? 'Takeaway Paused' : 'Takeaway Active' }}
                </button>
                
                <button wire:click="toggleReservations" class="relative inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider transition-colors {{ $reservationsPaused ? 'bg-red-100 text-red-800 border border-red-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $reservationsPaused ? 'bg-red-500' : 'bg-green-500' }}"></span>
                    {{ $reservationsPaused ? 'Reservations Paused' : 'Reservations Active' }}
                </button>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8 pb-12">
        
        <!-- Orders Kanban (3 Columns) -->
        <div class="xl:col-span-3">
            <h2 class="text-lg font-serif font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Takeaway Order Kanban</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Received -->
                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 min-h-[500px]">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 flex justify-between items-center">
                        Received
                        <span class="bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full">{{ $pendingOrders->count() }}</span>
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($pendingOrders as $order)
                            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-gray-900">{{ $order->reference }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $order->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">{{ $order->customer_name }}</div>
                                <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                    <a href="{{ route('admin.orders.show', $order->reference) }}" class="text-xs text-vanniyan-green-900 font-bold hover:underline">View Details</a>
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'confirmed')" class="px-3 py-1 bg-blue-100 text-blue-800 text-[10px] font-bold uppercase tracking-wider rounded hover:bg-blue-200 transition-colors">
                                        Confirm &rarr;
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @if($pendingOrders->isEmpty())
                            <div class="text-center p-4 text-xs text-gray-400 italic">No received orders.</div>
                        @endif
                    </div>
                </div>

                <!-- Confirmed -->
                <div class="bg-blue-50/30 rounded-xl border border-blue-100 p-4 min-h-[500px]">
                    <h3 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-4 flex justify-between items-center">
                        Confirmed
                        <span class="bg-blue-200 text-blue-800 px-2 py-0.5 rounded-full">{{ $confirmedOrders->count() }}</span>
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($confirmedOrders as $order)
                            <div class="bg-white rounded-lg border border-blue-200 shadow-sm p-4 border-l-4 border-l-blue-400 hover:shadow transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-gray-900">{{ $order->reference }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">{{ $order->customer_name }}</div>
                                <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                    <a href="{{ route('admin.orders.show', $order->reference) }}" class="text-xs text-vanniyan-green-900 font-bold hover:underline">View Details</a>
                                    <button wire:click="updateOrderStatus({{ $order->id }}, 'completed')" class="px-3 py-1 bg-green-100 text-green-800 text-[10px] font-bold uppercase tracking-wider rounded hover:bg-green-200 transition-colors">
                                        Mark Completed &rarr;
                                    </button>
                                </div>
                            </div>
                        @endforeach
                        @if($confirmedOrders->isEmpty())
                            <div class="text-center p-4 text-xs text-gray-400 italic">No confirmed orders.</div>
                        @endif
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-green-50/30 rounded-xl border border-green-100 p-4 min-h-[500px]">
                    <h3 class="text-xs font-bold text-green-700 uppercase tracking-wider mb-4 flex justify-between items-center">
                        Completed
                        <span class="bg-green-200 text-green-800 px-2 py-0.5 rounded-full">{{ $completedOrders->count() }}</span>
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($completedOrders as $order)
                            <div class="bg-white rounded-lg border border-green-200 shadow-sm p-4 border-l-4 border-l-green-500 hover:shadow transition-shadow">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="font-bold text-gray-900">{{ $order->reference }}</div>
                                    <div class="text-[10px] text-gray-400 font-bold">{{ $order->created_at->format('h:i A') }}</div>
                                </div>
                                <div class="text-sm text-gray-600 mb-3">{{ $order->customer_name }}</div>
                                <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                                    <a href="{{ route('admin.orders.show', $order->reference) }}" class="text-xs text-vanniyan-green-900 font-bold hover:underline">View Details</a>
                                    <span class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-green-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Done
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        @if($completedOrders->isEmpty())
                            <div class="text-center p-4 text-xs text-gray-400 italic">No completed orders.</div>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- Today's Reservations -->
        <div class="xl:col-span-1">
            <h2 class="text-lg font-serif font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Today's Reservations</h2>
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden min-h-[500px]">
                @if($reservations->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($reservations as $res)
                            <div class="p-4 hover:bg-gray-50 transition-colors {{ $res->status === 'pending' ? 'bg-yellow-50/10' : '' }}">
                                <div class="flex justify-between items-start mb-1">
                                    <div class="font-bold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($res->reservation_time)->format('h:i A') }}</div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider {{ $res->status === 'confirmed' ? 'text-blue-600' : 'text-yellow-600' }}">{{ $res->status }}</div>
                                </div>
                                <div class="text-sm font-medium text-gray-800">{{ $res->customer_name }} ({{ $res->guests }} pax)</div>
                                <div class="text-xs text-gray-500 mt-1 flex justify-between items-center">
                                    <span>{{ $res->table ? 'Table ' . $res->table->table_number : 'Unassigned' }}</span>
                                    <a href="{{ route('admin.bookings.show', $res->reservation_reference) }}" class="text-vanniyan-green-900 font-bold hover:underline">Manage</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-sm text-gray-500 italic">
                        No upcoming reservations for today.
                    </div>
                @endif
                <div class="p-3 bg-gray-50 border-t border-gray-200 text-center">
                    <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-vanniyan-green-900 uppercase tracking-wider hover:underline">View All Reservations</a>
                </div>
            </div>
        </div>

    </div>
</div>
