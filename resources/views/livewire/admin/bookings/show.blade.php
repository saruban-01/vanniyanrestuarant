<div>
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('admin.bookings.index') }}" class="mr-4 text-gray-500 hover:text-gray-900">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    Booking Details
                    @if($type === 'table')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 uppercase tracking-wider">Table</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 uppercase tracking-wider">Venue</span>
                    @endif
                </h1>
                <p class="text-sm text-gray-500">Ref: {{ $reference }}</p>
            </div>
        </div>
        
        <div>
            @php
                $status = $type === 'table' ? ($booking->status === 'pending' ? 'requested' : str_replace('_', '-', $booking->status)) : $booking->status;
                
                $colors = [
                    'requested' => 'bg-yellow-100 text-yellow-800',
                    'contacted' => 'bg-indigo-100 text-indigo-800',
                    'confirmed' => 'bg-green-100 text-green-800',
                    'declined' => 'bg-red-100 text-red-800',
                    'cancelled' => 'bg-gray-100 text-gray-800',
                    'completed' => 'bg-teal-100 text-teal-800',
                    'no-show' => 'bg-orange-100 text-orange-800',
                ];
                $statusClass = $colors[$status] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded text-sm font-bold uppercase tracking-wider border {{ $statusClass }}">
                Status: {{ ucfirst($status) }}
            </span>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider">Customer Details</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Name</dt>
                            <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->customer_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Phone</dt>
                            <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->phone }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Email</dt>
                            <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->email ?? 'Not provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider">Event / Booking Details</h3>
                </div>
                <div class="px-6 py-5">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        
                        @if($type === 'table')
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->reservation_date->format('l, F j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Time & Duration</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($booking->reservation_time)->format('h:i A') }} ({{ $booking->duration_minutes }} min)
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Guests</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->guests }} People</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Table Assignment</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">
                                    {{ optional($booking->table)->name ?? 'Not Assigned' }}
                                </dd>
                            </div>
                        @else
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Event Title</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->event_title }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Event Type</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ optional($booking->eventType)->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->event_date->format('l, F j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Time</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('h:i A') : 'TBD' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Guests</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ $booking->guest_count }} People</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Venue</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">{{ optional($booking->venue)->name ?? 'Not Assigned' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Requested Services</dt>
                                <dd class="mt-1 text-base text-gray-900 font-medium">
                                    @if($booking->services->count() > 0)
                                        <ul class="list-disc pl-5">
                                            @foreach($booking->services as $service)
                                                <li>{{ $service->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        Venue Only
                                    @endif
                                </dd>
                            </div>
                        @endif
                        
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-bold text-gray-500 uppercase tracking-wider">Special Requests</dt>
                            <dd class="mt-1 text-base text-gray-900 bg-gray-50 p-4 rounded-md border border-gray-100 whitespace-pre-wrap">{{ $booking->special_request ?: 'None provided' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider">Workflow Actions</h3>
                </div>
                <div class="px-6 py-5 space-y-3">
                    
                    @if($status === 'requested')
                        <button wire:click="updateStatus('contacted')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-transparent text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Mark as Contacted
                        </button>
                    @endif

                    @if(in_array($status, ['requested', 'contacted']))
                        <button wire:click="updateStatus('confirmed')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-transparent text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                            Confirm Booking
                        </button>
                        <button wire:click="updateStatus('declined')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-red-600 bg-white hover:bg-red-50 mt-2">
                            Decline Request
                        </button>
                    @endif

                    @if($status === 'confirmed')
                        <button wire:click="updateStatus('completed')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-transparent text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-white bg-teal-600 hover:bg-teal-700">
                            Mark Completed
                        </button>
                        <button wire:click="updateStatus('cancelled')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 mt-2">
                            Cancel Booking
                        </button>
                        @if($type === 'table')
                            <button wire:click="updateStatus('no_show')" class="w-full justify-center inline-flex items-center px-4 py-3 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-md shadow-sm text-orange-700 bg-white hover:bg-orange-50 mt-2">
                                Mark No-Show
                            </button>
                        @endif
                    @endif

                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider">Admin Notes</h3>
                </div>
                <div class="px-6 py-5">
                    <form wire:submit="saveNotes">
                        <textarea wire:model="adminNotes" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-vanniyan-green-500 focus:ring-vanniyan-green-500 sm:text-sm" placeholder="Internal notes..."></textarea>
                        <div class="mt-3 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-900 hover:bg-gray-800 focus:outline-none">
                                Save Notes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
        
    </div>
</div>
