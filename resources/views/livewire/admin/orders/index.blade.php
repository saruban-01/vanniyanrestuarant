<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Takeaway Orders</h1>
            <p class="text-gray-500 text-sm">Manage all customer takeaway and pickup orders.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 flex flex-col lg:flex-row gap-4 items-end justify-between bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
            <!-- Search -->
            <div class="w-full sm:w-64">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Search Orders</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Reference, Name, Phone..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full sm:w-48">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Status</label>
                <select wire:model.live="status" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
                    <option value="ALL">All Statuses</option>
                    <option value="RECEIVED">Received</option>
                    <option value="CONFIRMED">Confirmed</option>
                    <option value="PREPARING">Preparing</option>
                    <option value="READY">Ready</option>
                    <option value="COMPLETED">Completed</option>
                    <option value="CANCELLED">Cancelled</option>
                </select>
            </div>
        </div>

        <!-- Date Tabs -->
        <div class="flex bg-gray-100 p-1 rounded-lg w-full lg:w-auto overflow-x-auto">
            <button wire:click="setDateFilter('ALL')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'ALL' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">All</button>
            <button wire:click="setDateFilter('ACTIVE')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'ACTIVE' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Active</button>
            <button wire:click="setDateFilter('TODAY')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'TODAY' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Today</button>
            <button wire:click="setDateFilter('UPCOMING')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'UPCOMING' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Upcoming</button>
            <button wire:click="setDateFilter('COMPLETED')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'COMPLETED' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Completed</button>
            <button wire:click="setDateFilter('CANCELLED')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateFilter === 'CANCELLED' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Cancelled</button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">Order</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Pickup</th>
                            <th class="px-6 py-4 text-center">Items</th>
                            <th class="px-6 py-4 text-right">Total</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Created</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50 transition-colors {{ $order->status === 'received' ? 'bg-red-50/30' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $order->reference }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $order->pickup_time->format('h:i A') }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $order->pickup_time->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-0.5 rounded-full">
                                        {{ $order->items->sum('quantity') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-bold text-gray-900">Rs. {{ number_format($order->total, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColors = [
                                            'received' => 'bg-red-100 text-red-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-gray-100 text-gray-600',
                                        ];
                                        $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $color }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-gray-500">
                                    {{ $order->created_at->format('d M, h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-right text-xs font-medium">
                                    <a href="{{ route('admin.orders.show', $order->reference) }}" class="text-vanniyan-green-900 hover:text-vanniyan-gold font-bold uppercase tracking-wider underline">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                <h3 class="text-lg font-serif font-bold text-gray-900 mb-2">No Orders Found</h3>
                <p class="text-gray-500">No takeaway orders match your current filters.</p>
            </div>
        @endif
    </div>
</div>
