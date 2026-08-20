<div class="bg-gray-50 min-h-screen py-12 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-10">
            @if($booking->status === 'confirmed')
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">Your Venue Booking is Confirmed</h1>
                <p class="mt-4 text-lg text-gray-600 font-light">Your booking for Vanniyan's venue has been confirmed.</p>
            @elseif($booking->status === 'requested')
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">Venue Booking Request Received</h1>
                <p class="mt-4 text-lg text-gray-600 font-light">We have received your request. Our team will contact you to confirm the availability and details of your venue booking.</p>
            @elseif($booking->status === 'contacted')
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">Request Under Review</h1>
                <p class="mt-4 text-lg text-gray-600 font-light">We are currently processing your venue booking request.</p>
            @else
                <h1 class="text-3xl sm:text-4xl font-serif font-bold text-vanniyan-green-900 mb-5">Booking Status: {{ ucfirst($booking->status) }}</h1>
            @endif

            <p class="mt-6 text-sm font-bold text-gray-500 uppercase tracking-widest">Reference: <span class="text-gray-900 bg-gray-200 px-2 py-1 rounded">{{ $booking->reference }}</span></p>
            <p class="mt-2 text-xs text-gray-400">Save this URL to check the status of your request.</p>
        </div>

        <div class="bg-white shadow-sm rounded border border-gray-200 overflow-hidden mb-8 p-8">
            <h2 class="text-xl font-serif font-bold text-gray-900 uppercase tracking-widest mb-6 border-b border-gray-200 pb-4">Booking Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Venue</span>
                        <span class="text-gray-900 font-medium">{{ optional($booking->venue)->name ?? 'Unknown Venue' }}</span>
                    </div>
                    
                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date</span>
                        <span class="text-gray-900 font-medium">{{ $booking->event_date->format('l, F j, Y') }}</span>
                    </div>

                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Time</span>
                        <span class="text-gray-900 font-medium">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'TBD' }}
                        </span>
                    </div>
                </div>
                
                <div>
                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guests</span>
                        <span class="text-gray-900 font-medium">{{ $booking->guest_count }} People</span>
                    </div>

                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Event Type</span>
                        <span class="text-gray-900 font-medium">{{ optional($booking->eventType)->name }}</span>
                    </div>

                    <div class="mb-6">
                        <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Requested Services</span>
                        <span class="text-gray-900 font-medium">
                            @if($booking->services->count() > 0)
                                {{ $booking->services->pluck('name')->join(', ') }}
                            @else
                                Venue Only
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-100">
                <span class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Customer Details</span>
                <span class="text-gray-900 font-medium">{{ $booking->customer_name }} | {{ $booking->phone }}</span>
            </div>
        </div>

    </div>
</div>
