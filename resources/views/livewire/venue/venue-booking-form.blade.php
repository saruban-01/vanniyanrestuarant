<div class="bg-vanniyan-white min-h-screen py-12 sm:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs md:text-sm block mb-4">Book With Us</span>
            <h1 class="text-3xl font-serif font-bold text-vanniyan-green-900 sm:text-4xl mb-5">Outdoor Garden Venue</h1>
            <div class="w-14 h-px bg-vanniyan-gold mx-auto mb-7"></div>
            <p class="mt-4 text-lg text-gray-600 font-light">Available for customers who want to organize their own events.</p>
        </div>

        @if($currentStep === 7)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-vanniyan-green-900 mx-auto mb-6"></div>
                <h3 class="text-2xl font-serif font-bold text-vanniyan-green-900">Submitting Request...</h3>
            </div>
        @else
            <!-- Progress Tracker -->
            <div class="mb-12">
                <div class="hidden sm:flex items-center justify-between">
                    @foreach(['Date', 'Time', 'Guests', 'Event', 'Details', 'Review'] as $i => $label)
                        <div class="flex items-center {{ $i < 5 ? 'flex-1' : '' }}">
                            <div class="flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center border-2 transition-colors
                                    {{ $currentStep > $i + 1 ? 'bg-vanniyan-green-900 border-vanniyan-green-900 text-white' : ($currentStep === $i + 1 ? 'border-vanniyan-gold text-vanniyan-gold' : 'border-gray-300 text-gray-400') }}">
                                    @if($currentStep > $i + 1)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <span class="text-xs font-bold">{{ $i + 1 }}</span>
                                    @endif
                                </div>
                                <span class="mt-2 text-xs font-bold uppercase tracking-widest {{ $currentStep >= $i + 1 ? 'text-vanniyan-green-900' : 'text-gray-400' }}">{{ $label }}</span>
                            </div>
                            @if($i < 5)
                                <div class="flex-1 h-0.5 mx-3 mb-5 transition-colors {{ $currentStep > $i + 1 ? 'bg-vanniyan-green-900' : 'bg-gray-200' }}"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="sm:hidden text-center text-xs font-bold uppercase tracking-widest text-vanniyan-green-900">
                    Step {{ $currentStep }} of 6
                </div>
                <div class="mt-4 h-1 w-full bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-vanniyan-green-900 transition-all duration-300" style="width: {{ ($currentStep / 6) * 100 }}%"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden p-8 sm:p-12">

                <!-- STEP 1: DATE -->
                @if($currentStep === 1)
                    <div class="text-center max-w-lg mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6">When is your event?</h2>
                        <input type="date" wire:model.live="event_date" class="block w-full md:w-1/2 mx-auto text-center text-xl font-medium border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-4" min="{{ now()->format('Y-m-d') }}">
                        @error('event_date') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror

                        <div class="mt-12">
                            <button wire:click="goToStep(2)" class="w-full sm:w-auto inline-flex justify-center items-center px-10 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                @endif

                <!-- STEP 2: TIME -->
                @if($currentStep === 2)
                    <div class="max-w-lg mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6 text-center">What Time?</h2>
                        @php
                            $timeSlots = [];
                            for ($h = 9; $h <= 21; $h++) {
                                $timeSlots[] = sprintf('%02d:00', $h);
                            }
                        @endphp
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Start Time</label>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                @foreach($timeSlots as $slot)
                                    <button wire:click="$set('start_time', '{{ $slot }}')" class="w-full py-3 text-sm font-bold rounded-lg border transition-colors {{ $start_time === $slot ? 'bg-vanniyan-green-900 text-white border-vanniyan-green-900' : 'bg-white text-vanniyan-green-900 border-gray-300 hover:border-vanniyan-green-900 hover:bg-vanniyan-green-50' }}">
                                        {{ \Carbon\Carbon::parse($slot)->format('g A') }}
                                    </button>
                                @endforeach
                            </div>
                            @error('start_time') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="mt-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-3 text-left">Duration</label>
                            <select wire:model.live="duration" class="block w-full text-lg border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                <option value="2">2 Hours</option>
                                <option value="3">3 Hours</option>
                                <option value="4">4 Hours</option>
                                <option value="5">5 Hours</option>
                                <option value="6">6 Hours</option>
                                <option value="8">8 Hours</option>
                                <option value="12">12 Hours (Full Day)</option>
                            </select>
                            @error('duration') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-12 flex justify-between gap-4">
                            <button wire:click="goToStep(1)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button wire:click="goToStep(3)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                @endif

                <!-- STEP 3: GUESTS -->
                @if($currentStep === 3)
                    <div class="text-center max-w-lg mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6">How many guests?</h2>

                        <div class="flex items-center justify-center gap-6 mb-8">
                            <button wire:click="$set('guest_count', {{ max(1, (int)$guest_count - 5) }})" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-vanniyan-green-900 transition-colors focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </button>
                            <input type="number" wire:model.live="guest_count" class="w-32 text-center text-3xl font-serif font-bold border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-vanniyan-green-900 p-2 bg-transparent" min="1">
                            <button wire:click="$set('guest_count', {{ (int)$guest_count + 5 }})" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-50 hover:text-vanniyan-green-900 transition-colors focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                        @error('guest_count') <span class="text-sm font-bold text-red-600 block mb-6">{{ $message }}</span> @enderror

                        <div class="mt-12 flex justify-between gap-4">
                            <button wire:click="goToStep(2)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button wire:click="goToStep(4)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                @endif

                <!-- STEP 4: EVENT -->
                @if($currentStep === 4)
                    <div class="max-w-lg mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6 text-center">What are you hosting?</h2>

                        <div class="space-y-6">
                            @if(count($venues) > 1)
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Venue</label>
                                    <select wire:model="venueId" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                        <option value="">Select a venue...</option>
                                        @foreach($venues as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }} (Up to {{ $v->max_capacity }} guests)</option>
                                        @endforeach
                                    </select>
                                    @error('venueId') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                                </div>
                            @else
                                <div class="bg-gray-50 border border-gray-200 rounded p-4 text-center">
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Venue</div>
                                    <div class="font-bold text-vanniyan-green-900 uppercase tracking-wide">{{ $venues->first()->name ?? 'Outdoor Garden' }}</div>
                                </div>
                            @endif

                            @if(count($eventTypes) > 0)
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Event Type (Optional)</label>
                                    <select wire:model="event_type_id" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                        <option value="">Select an event type (or enter below)...</option>
                                        @foreach($eventTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('event_type_id') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Event Name / Custom Type</label>
                                <input type="text" wire:model="event_title" placeholder="e.g. Birthday Party, Corporate Retreat" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                @error('event_title') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-12 flex justify-between gap-4">
                            <button wire:click="goToStep(3)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button wire:click="goToStep(5)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Next
                            </button>
                        </div>
                    </div>
                @endif

                <!-- STEP 5: DETAILS -->
                @if($currentStep === 5)
                    <div class="max-w-lg mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6 text-center">Your Details</h2>

                        <div class="space-y-6">
                            <div>
                                <input type="text" wire:model="customer_name" placeholder="Full Name" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                @error('customer_name') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <input type="tel" wire:model="phone" placeholder="Mobile Number" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                @error('phone') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <input type="email" wire:model="email" placeholder="Email (Optional)" class="block w-full border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 py-3">
                                @error('email') <span class="text-sm font-bold text-red-600 mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-12 flex justify-between gap-4">
                            <button wire:click="goToStep(4)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button wire:click="goToStep(6)" class="w-1/2 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Review
                            </button>
                        </div>
                    </div>
                @endif

                <!-- STEP 6: REVIEW -->
                @if($currentStep === 6)
                    <div class="max-w-xl mx-auto">
                        <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-6 text-center">Review Your Venue Booking</h2>

                        <div class="bg-gray-50 p-6 rounded border border-gray-200 mb-8 space-y-6">
                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Venue</div>
                                <div class="col-span-2 font-medium text-gray-900">{{ $venue ? $venue->name : 'N/A' }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</div>
                                <div class="col-span-2 font-medium text-gray-900">{{ $event_date ? \Carbon\Carbon::parse($event_date)->format('d F Y') : 'N/A' }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Time</div>
                                <div class="col-span-2 font-medium text-gray-900">
                                    {{ $start_time ? \Carbon\Carbon::parse($start_time)->format('h:i A') : 'N/A' }}
                                    (for {{ $duration }} hours)
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Guests</div>
                                <div class="col-span-2 font-medium text-gray-900">{{ $guest_count }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Event</div>
                                <div class="col-span-2 font-medium text-gray-900">{{ $event_title }}</div>
                            </div>

                            <div class="grid grid-cols-3 gap-4 border-b border-gray-200 pb-4">
                                <div class="col-span-1 text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</div>
                                <div class="col-span-2 font-medium text-gray-900">{{ $customer_name }}</div>
                            </div>
                        </div>

                        @if($availabilityMessage)
                            <div class="mb-8 p-4 bg-red-50 text-red-800 border border-red-100 rounded text-sm font-medium">
                                {{ $availabilityMessage }}
                            </div>
                        @endif

                        <div class="bg-gray-100 rounded border border-gray-200 p-6 mb-8 text-center">
                            <p class="text-sm font-bold text-gray-800 uppercase tracking-wide">
                                The customer organizes the event. Vanniyan provides the booked venue space.
                            </p>
                            <p class="text-xs text-gray-500 mt-2">
                                Our team will contact you to confirm the availability and details of your venue booking.
                            </p>
                        </div>

                        <div class="flex justify-between gap-4">
                            <button wire:click="goToStep(5)" class="w-1/3 inline-flex justify-center items-center px-6 py-4 border border-gray-300 text-sm font-bold uppercase tracking-wider rounded-full text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                Back
                            </button>
                            <button wire:click="submitBooking" class="w-2/3 inline-flex justify-center items-center px-6 py-4 border border-transparent text-sm font-bold uppercase tracking-wider rounded-full text-white bg-vanniyan-green-900 hover:bg-vanniyan-green-800 transition-colors">
                                Request Venue
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-4 font-medium text-center">By submitting this request, you acknowledge our <a href="{{ route('privacy-policy') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Privacy Policy</a> and agree to our <a href="{{ route('terms-and-conditions') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Terms &amp; Conditions</a>.</p>
                        </div>
                    </div>
                @endif

            </div>
        @endif
    </div>
</div>