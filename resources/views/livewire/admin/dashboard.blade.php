<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Today at Vanniyan</h1>
            <p class="text-gray-500 text-sm">Welcome back, {{ $adminName }}</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded font-bold text-xs uppercase tracking-wider {{ $isOpen ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                Restaurant {{ $isOpen ? 'Open Now' : 'Closed Now' }}
            </div>
            <a href="{{ route('admin.operations') }}" class="px-6 py-2 bg-vanniyan-green-900 text-white rounded text-sm font-bold uppercase tracking-wider hover:bg-vanniyan-green-800 transition-colors shadow-sm">
                Daily Operations
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8 overflow-x-auto pb-2">
        <div class="flex gap-4">
            <a href="{{ route('admin.orders') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm transition-colors">
                + New Order
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm transition-colors">
                View Bookings
            </a>
            <a href="{{ route('admin.menu.item.create') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm transition-colors">
                + New Menu Item
            </a>
            <a href="{{ route('admin.settings') }}" class="flex-shrink-0 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 rounded text-xs font-bold text-gray-700 uppercase tracking-wider shadow-sm transition-colors">
                Edit Hours
            </a>
        </div>
    </div>

    <!-- Today's KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Takeaway Orders</div>
            <div class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $kpis['takeaway'] }}</div>
            <div class="text-xs text-gray-400 mt-2">Today's count</div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Reservations</div>
            <div class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $kpis['reservations'] }}</div>
            <div class="text-xs text-gray-400 mt-2">Today confirmed/pending</div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Venue Bookings</div>
            <div class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $kpis['venues'] }}</div>
            <div class="text-xs text-gray-400 mt-2">Upcoming/active bookings</div>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Contact Messages</div>
            <div class="text-3xl font-serif font-bold text-vanniyan-green-900">{{ $kpis['messages'] }}</div>
            <div class="text-xs text-gray-400 mt-2">Unread/new count</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 pb-32">
        <!-- Needs Attention -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white border border-red-200 rounded-xl shadow-sm p-6 sm:p-8">
                <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-red-700 uppercase tracking-wide flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-600"></span> Needs Attention
                    </h2>
                    @if($attentionCount > 0)
                    <span class="bg-red-100 text-red-800 text-xs font-bold px-2 py-1 rounded-full">{{ $attentionCount }} Items</span>
                    @endif
                </div>
                
                @if($attentionCount == 0)
                    <p class="text-gray-500 text-sm">All caught up! No items need your immediate attention.</p>
                @else
                    <div class="space-y-4">
                        @if(count($needsAttention['orders']) > 0)
                            <div class="flex justify-between items-center p-4 bg-red-50 rounded-lg border border-red-100">
                                <div>
                                    <div class="font-bold text-red-900">{{ count($needsAttention['orders']) }} New Takeaway {{ count($needsAttention['orders']) === 1 ? 'Order' : 'Orders' }}</div>
                                    <div class="text-xs text-red-700 mt-1">Orders waiting to be confirmed</div>
                                </div>
                                <a href="{{ route('admin.orders') }}?status=RECEIVED" class="text-xs font-bold text-red-800 hover:text-red-900 uppercase tracking-wider underline">View All</a>
                            </div>
                        @endif

                        @if(count($needsAttention['reservations']) > 0)
                            <div class="flex justify-between items-center p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                                <div>
                                    <div class="font-bold text-yellow-900">{{ count($needsAttention['reservations']) }} Pending {{ count($needsAttention['reservations']) === 1 ? 'Reservation' : 'Reservations' }}</div>
                                    <div class="text-xs text-yellow-700 mt-1">Table reservations needing confirmation</div>
                                </div>
                                <a href="{{ route('admin.bookings.index') }}?filter=requested" class="text-xs font-bold text-yellow-800 hover:text-yellow-900 uppercase tracking-wider underline">View All</a>
                            </div>
                        @endif

                        @if(count($needsAttention['venue_bookings']) > 0)
                            <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div>
                                    <div class="font-bold text-blue-900">{{ count($needsAttention['venue_bookings']) }} New Venue {{ count($needsAttention['venue_bookings']) === 1 ? 'Request' : 'Requests' }}</div>
                                    <div class="text-xs text-blue-700 mt-1">Venue requests needing review</div>
                                </div>
                                <a href="{{ route('admin.bookings.index') }}?filter=requested" class="text-xs font-bold text-blue-800 hover:text-blue-900 uppercase tracking-wider underline">View All</a>
                            </div>
                        @endif

                        @if(count($needsAttention['messages']) > 0)
                            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div>
                                    <div class="font-bold text-gray-900">{{ count($needsAttention['messages']) }} Unread Contact {{ count($needsAttention['messages']) === 1 ? 'Message' : 'Messages' }}</div>
                                    <div class="text-xs text-gray-500 mt-1">New customer inquiries</div>
                                </div>
                                <a href="{{ route('admin.contact.messages') }}" class="text-xs font-bold text-gray-700 hover:text-gray-900 uppercase tracking-wider underline">View All</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Today's Timeline -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sm:p-8 sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-wide mb-6 pb-4 border-b border-gray-100">Today's Timeline</h2>
                
                @if(count($timeline) == 0)
                    <p class="text-gray-500 text-sm">No scheduled events, reservations, or takeaway pickups for today.</p>
                @else
                    <div class="relative border-l-2 border-gray-100 ml-3 space-y-8 mt-4">
                        @foreach($timeline as $item)
                            <div class="relative pl-6">
                                <span class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white {{ $item['type'] === 'Reservation' ? 'bg-vanniyan-gold' : ($item['type'] === 'Venue' ? 'bg-vanniyan-green-900' : 'bg-blue-500') }}"></span>
                                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ $item['time'] }}</div>
                                <div class="font-bold text-gray-900">{{ $item['title'] }}</div>
                                <div class="text-sm text-gray-500 mb-2">{{ $item['subtitle'] }}</div>
                                <a href="{{ $item['link'] }}" class="text-xs font-bold text-vanniyan-green-900 hover:text-vanniyan-gold uppercase tracking-wider underline">View</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
