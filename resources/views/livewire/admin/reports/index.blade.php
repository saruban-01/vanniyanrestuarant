<div>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2 uppercase tracking-widest">Analytics & Reporting</h1>
            <p class="text-gray-500 text-sm">Analyze sales, reservations, and event performance.</p>
        </div>
        
        <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-lg">
            <button wire:click="setDateRange('today')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateRange === 'today' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Today</button>
            <button wire:click="setDateRange('7days')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateRange === '7days' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Last 7 Days</button>
            <button wire:click="setDateRange('30days')" class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-md whitespace-nowrap {{ $dateRange === '30days' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">Last 30 Days</button>
        </div>
    </div>

    <!-- Custom Date Range -->
    <div class="mb-8 bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Start Date</label>
            <input type="date" wire:model.live="startDate" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">End Date</label>
            <input type="date" wire:model.live="endDate" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 sm:text-sm">
        </div>
        <div class="text-sm text-gray-500 pb-2">
            Showing data from <span class="font-bold text-gray-900">{{ Carbon\Carbon::parse($startDate)->format('M d, Y') }}</span> to <span class="font-bold text-gray-900">{{ Carbon\Carbon::parse($endDate)->format('M d, Y') }}</span>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-green-50 opacity-50"></div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Total Revenue</div>
                <div class="text-3xl font-serif font-bold text-gray-900">Rs. {{ number_format($data['totalRevenue'], 2) }}</div>
            </div>
        </div>

        <!-- Takeaway Sales -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-blue-50 opacity-50"></div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Takeaway Orders</div>
                <div class="text-2xl font-bold text-gray-900">{{ $data['takeawayCount'] }}</div>
                <div class="text-sm text-blue-600 font-bold mt-1">Rs. {{ number_format($data['takeawaySales'], 2) }}</div>
            </div>
        </div>

        <!-- Reservations -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-purple-50 opacity-50"></div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Dine-in Reservations</div>
                <div class="text-2xl font-bold text-gray-900">{{ $data['reservationCount'] }}</div>
                <div class="text-sm text-purple-600 font-bold mt-1">{{ $data['reservationGuests'] }} Guests</div>
            </div>
        </div>

        <!-- Venue Bookings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-yellow-50 opacity-50"></div>
            <div class="relative z-10">
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Venue Bookings</div>
                <div class="text-2xl font-bold text-gray-900">{{ $data['eventGuestCount'] }} <span class="text-sm text-gray-500 font-normal">Guests</span></div>
                <div class="text-sm text-yellow-600 font-bold mt-1">Pending Quote</div>
            </div>
        </div>

    </div>

    <!-- Revenue Chart Area -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-8">
        <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-4 mb-6">Revenue Trend</h2>
        
        <!-- Simple CSS Bar Chart Representation for Dashboard -->
        <div class="h-64 flex items-end gap-2 px-4 pb-8 relative pt-4">
            <!-- Y-Axis Scale (approximate max) -->
            @php
                $maxVal = 1;
                foreach($data['chartData'] as $point) {
                    $total = $point['takeaway'] + $point['events'];
                    if($total > $maxVal) $maxVal = $total;
                }
            @endphp
            
            @foreach($data['chartData'] as $point)
                @php
                    $takeawayHeight = ($point['takeaway'] / $maxVal) * 100;
                    $eventsHeight = ($point['events'] / $maxVal) * 100;
                @endphp
                <div class="flex-1 flex flex-col justify-end group relative items-center h-full">
                    
                    <!-- Tooltip -->
                    <div class="absolute bottom-full mb-2 hidden group-hover:block z-20 w-32 -ml-16 left-1/2">
                        <div class="bg-gray-900 text-white text-xs rounded py-1 px-2 text-center shadow-lg">
                            <div class="font-bold border-b border-gray-700 pb-1 mb-1">{{ $point['date'] }}</div>
                            <div class="flex justify-between"><span>Takeaway:</span> <span>{{ number_format($point['takeaway']) }}</span></div>
                            <div class="flex justify-between"><span>Events:</span> <span>{{ number_format($point['events']) }}</span></div>
                        </div>
                    </div>

                    <!-- Bars -->
                    <div class="w-full max-w-[40px] bg-yellow-400 rounded-t-sm" style="height: {{ $eventsHeight }}%"></div>
                    <div class="w-full max-w-[40px] bg-blue-500 rounded-b-sm" style="height: {{ $takeawayHeight }}%"></div>
                    
                    <!-- X-Axis Label -->
                    <div class="absolute top-full mt-2 text-[10px] text-gray-400 font-bold transform -rotate-45 origin-top-left truncate w-16">
                        {{ $point['date'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="flex justify-center gap-6 mt-4 pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-blue-500"></span>
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Takeaway Sales</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-yellow-400"></span>
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Venue Bookings (N/A)</span>
            </div>
        </div>
    </div>
    
</div>
