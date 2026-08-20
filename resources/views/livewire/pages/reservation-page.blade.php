<div class="bg-[#F7F7F5] min-h-screen">
    
    <!-- HERO SECTION -->
    <div class="w-full h-[40vh] md:h-[50vh] bg-vanniyan-green-900 relative">
        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80" alt="Vanniyan Dining Room" class="absolute inset-0 w-full h-full object-cover opacity-60 blur-[2px]">
        <div class="absolute inset-0 bg-vanniyan-green-900/50 backdrop-blur-sm"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-vanniyan-green-900 via-transparent to-transparent"></div>
        
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <span class="text-xs font-bold text-vanniyan-gold uppercase tracking-[0.2em] mb-4">Vanniyan Restaurant</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white mb-5">Reserve Your Table</h1>
            <div class="w-14 h-px bg-vanniyan-gold mb-6"></div>
            <p class="text-gray-200 text-lg md:text-xl font-light max-w-xl">Plan your visit and enjoy the Vanniyan dining experience.</p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-16">
            
            <!-- LEFT: BOOKING FLOW -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 md:p-10">
                    
                    @if($errorMessage)
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($step === 6)
                        <!-- SUCCESS STATE -->
                        <div class="text-center py-10">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-vanniyan-green-900/10 text-vanniyan-green-900 mb-6">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h2 class="text-3xl font-serif font-bold text-vanniyan-green-900 mb-2">Your Table is Reserved</h2>
                            <p class="text-gray-600 mb-8">We look forward to welcoming you to Vanniyan.</p>
                            
                            <div class="bg-[#F7F7F5] rounded-2xl p-6 mb-8 inline-block text-left border border-gray-100">
                                <p class="text-xs font-bold text-vanniyan-gold uppercase tracking-wider mb-1">Reservation Reference</p>
                                <p class="text-2xl font-mono font-bold text-vanniyan-green-900">{{ $reservationReference }}</p>
                            </div>

                            <div class="sm:flex sm:gap-4 sm:items-center">
                                <a href="{{ $settings['maps_url'] ?? 'https://maps.app.goo.gl/Kmo3SomPabUBTPs76' }}" target="_blank" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-vanniyan-green-800 transition-colors">
                                    Get Directions
                                </a>
                                <a href="{{ route('menu') }}" class="px-8 py-3 bg-white border border-gray-300 text-vanniyan-green-900 font-bold uppercase tracking-wider text-sm rounded-full hover:bg-gray-50 transition-colors">
                                    View Menu
                                </a>
                            </div>
                        </div>

                    @else
                        <!-- STEP 1: DATE -->
                        <div class="mb-12 {{ $step !== 1 ? 'opacity-50' : '' }}">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">01. When are you visiting?</h2>
                                <div class="w-8 h-[2px] bg-vanniyan-gold mb-6"></div>
                                @if($step > 1)
                                    <button wire:click="goToStep(1)" class="text-sm font-bold text-vanniyan-gold uppercase hover:text-yellow-600">Edit</button>
                                @endif
                            </div>
                            @if($step === 1)
                                <div class="w-full md:w-1/2">
                                    <input type="date" wire:model.live="date" min="{{ \Carbon\Carbon::today('Asia/Colombo')->format('Y-m-d') }}" max="{{ \Carbon\Carbon::today('Asia/Colombo')->addDays(30)->format('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900 text-gray-800">
                                    <p class="text-xs text-gray-500 mt-2">Reservations are currently available up to 30 days in advance.</p>
                                </div>
                                <div class="mt-6">
                                    <button wire:click="goToStep(2)" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-vanniyan-green-800 transition-colors">Next</button>
                                </div>
                            @else
                                <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($date)->format('l, j F Y') }}</p>
                            @endif
                        </div>

                        <!-- STEP 2: TIME -->
                        <div class="mb-12 {{ $step !== 2 ? 'opacity-50' : '' }} border-t border-gray-100 pt-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">02. What time?</h2>
                                <div class="w-8 h-[2px] bg-vanniyan-gold mb-6"></div>
                                @if($step > 2)
                                    <button wire:click="goToStep(2)" class="text-sm font-bold text-vanniyan-gold uppercase hover:text-yellow-600">Edit</button>
                                @endif
                            </div>
                            @if($step === 2)
                                @if(count($availableSlots) > 0)
                                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                        @foreach($availableSlots as $slot)
                                            <button wire:click="selectTime('{{ $slot['time'] }}')" class="py-3 px-2 text-sm font-bold rounded border transition-colors {{ $time === $slot['time'] ? 'bg-vanniyan-green-900 text-white border-vanniyan-green-900' : 'bg-white text-vanniyan-green-900 border-gray-300 hover:border-vanniyan-green-900' }}">
                                                {{ $slot['display_time'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-gray-50 border border-gray-200 rounded p-6 text-center">
                                        <p class="text-gray-600 font-medium">Reservations are not available for this date.</p>
                                        <p class="text-sm text-gray-500 mt-1">Please select another date.</p>
                                    </div>
                                @endif
                            @else
                                @if($time)
                                    <p class="text-lg text-gray-800">{{ \Carbon\Carbon::parse($time)->format('g:i A') }}</p>
                                @endif
                            @endif
                        </div>

                        <!-- STEP 3: GUESTS -->
                        <div class="mb-12 {{ $step !== 3 ? 'opacity-50' : '' }} border-t border-gray-100 pt-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">03. How many guests?</h2>
                                <div class="w-8 h-[2px] bg-vanniyan-gold mb-6"></div>
                                @if($step > 3)
                                    <button wire:click="goToStep(3)" class="text-sm font-bold text-vanniyan-gold uppercase hover:text-yellow-600">Edit</button>
                                @endif
                            </div>
                            @if($step === 3)
                                <div class="flex items-center space-x-4">
                                    <button wire:click="$set('guests', {{ max(1, $guests - 1) }})" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-xl text-gray-600 hover:border-vanniyan-green-900 hover:text-vanniyan-green-900 transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 focus:ring-offset-2">&minus;</button>
                                    <span class="text-3xl font-bold text-vanniyan-green-900 w-12 text-center">{{ $guests }}</span>
                                    <button wire:click="$set('guests', {{ min(10, $guests + 1) }})" class="w-12 h-12 rounded-full border border-gray-300 flex items-center justify-center text-xl text-gray-600 hover:border-vanniyan-green-900 hover:text-vanniyan-green-900 transition-colors focus:outline-none focus:ring-2 focus:ring-vanniyan-green-900 focus:ring-offset-2">&plus;</button>
                                </div>
                                <div class="mt-8">
                                    <button wire:click="goToStep(4)" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-vanniyan-green-800 transition-colors">Next</button>
                                </div>
                            @else
                                <p class="text-lg text-gray-800">{{ $guests }} {{ $guests === 1 ? 'Guest' : 'Guests' }}</p>
                            @endif
                        </div>

                        <!-- STEP 4: DETAILS -->
                        <div class="mb-12 {{ $step !== 4 ? 'opacity-50' : '' }} border-t border-gray-100 pt-12">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">04. Your Details</h2>
                                <div class="w-8 h-[2px] bg-vanniyan-gold mb-6"></div>
                                @if($step > 4)
                                    <button wire:click="goToStep(4)" class="text-sm font-bold text-vanniyan-gold uppercase hover:text-yellow-600">Edit</button>
                                @endif
                            </div>
                            @if($step === 4)
                                <div class="space-y-6 max-w-lg">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name *</label>
                                        <input type="text" wire:model="customer_name" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                                        @error('customer_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Mobile Number *</label>
                                        <input type="tel" wire:model="phone" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900" placeholder="+94 77 123 4567">
                                        @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address (Optional)</label>
                                        <input type="email" wire:model="email" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900">
                                        @error('email') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Special Requests (Optional)</label>
                                        <textarea wire:model="special_request" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded focus:ring-vanniyan-green-900 focus:border-vanniyan-green-900" placeholder="e.g. Window seat if available"></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Requests are subject to availability.</p>
                                    </div>
                                    <div class="pt-4">
                                        <button wire:click="goToStep(5)" class="px-8 py-3 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider text-sm rounded-full hover:bg-vanniyan-green-800 transition-colors">Review Reservation</button>
                                    </div>
                                </div>
                            @else
                                @if($customer_name)
                                    <div class="text-lg text-gray-800">
                                        <p class="font-bold">{{ $customer_name }}</p>
                                        <p class="text-gray-600 text-base">{{ $phone }}</p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- STEP 5: REVIEW & CONFIRM -->
                        @if($step === 5)
                            <div class="border-t border-gray-100 pt-12">
                                <h2 class="text-2xl font-serif font-bold text-vanniyan-green-900 mb-2">05. Confirmation</h2>
                                <div class="w-8 h-[2px] bg-vanniyan-gold mb-6"></div>
                                
                                <div class="bg-gray-50 rounded-2xl p-6 mb-8 border border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Date</p>
                                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($date)->format('l, j F Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Time</p>
                                            <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($time)->format('g:i A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Guests</p>
                                            <p class="font-medium text-gray-900">{{ $guests }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Name</p>
                                            <p class="font-medium text-gray-900">{{ $customer_name }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Mobile</p>
                                            <p class="font-medium text-gray-900">{{ $phone }}</p>
                                        </div>
                                        @if($special_request)
                                            <div class="md:col-span-2">
                                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Special Request</p>
                                                <p class="font-medium text-gray-900">{{ $special_request }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <button wire:click="confirmReservation" wire:loading.attr="disabled" class="w-full sm:w-auto px-10 py-4 bg-vanniyan-green-900 text-white font-bold uppercase tracking-wider rounded shadow-md hover:bg-vanniyan-green-800 transition-all disabled:opacity-70 disabled:cursor-not-allowed">
                                    <span wire:loading.remove wire:target="confirmReservation">Confirm Reservation</span>
                                    <span wire:loading wire:target="confirmReservation">Confirming...</span>
                                </button>
                                <p class="text-xs text-gray-500 mt-4 font-medium text-center sm:text-left">By submitting this request, you acknowledge our <a href="{{ route('privacy-policy') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Privacy Policy</a> and agree to our <a href="{{ route('terms-and-conditions') }}" class="underline text-vanniyan-green-900 hover:text-vanniyan-gold transition-colors">Terms &amp; Conditions</a>.</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- RIGHT: SUMMARY (Sticky on Desktop) -->
            @if($step !== 6)
            <div class="w-full lg:w-1/3 hidden md:block">
                <div class="sticky top-24 bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-serif font-bold text-vanniyan-green-900 mb-6 border-b border-gray-100 pb-4">Your Reservation</h3>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Date</span>
                            <span class="font-medium text-right text-gray-900">{{ $date ? \Carbon\Carbon::parse($date)->format('D, j M Y') : '—' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Time</span>
                            <span class="font-medium text-right text-gray-900">{{ $time ? \Carbon\Carbon::parse($time)->format('g:i A') : '—' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-500">Guests</span>
                            <span class="font-medium text-right text-gray-900">{{ $guests }}</span>
                        </div>
                        
                        @if($customer_name)
                        <div class="border-t border-gray-100 pt-4 mt-4">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-gray-500">Name</span>
                                <span class="font-medium text-right text-gray-900 truncate max-w-[150px]">{{ $customer_name }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Restaurant",
      "name": "Vanniyan Restaurant",
      "image": "https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80",
      "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Kilinochchi",
        "addressRegion": "Northern Province",
        "addressCountry": "LK"
      },
      "acceptsReservations": "True"
    }
    </script>
</div>
