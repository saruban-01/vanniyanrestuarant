<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Venue Calendar</h1>
            <p class="mt-2 text-sm text-gray-700">View confirmed bookings and manage blockouts.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <button wire:click="openBlackoutModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-vanniyan-green-600 hover:bg-vanniyan-green-700">
                Add Blockout Date
            </button>
        </div>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">
                {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
            </h2>
            <div class="flex space-x-2">
                <button wire:click="previousMonth" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</button>
                <button wire:click="nextMonth" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Next</button>
            </div>
        </div>
        
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-7 gap-px border-b border-gray-200">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="text-center text-sm font-medium text-gray-500 py-2">{{ $day }}</div>
                @endforeach
            </div>
            
            <div class="grid grid-cols-7 gap-px bg-gray-200">
                @foreach($calendar as $day)
                    <div class="bg-white min-h-[120px] p-2 {{ !$day['isCurrentMonth'] ? 'bg-gray-50 text-gray-400' : '' }}">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium {{ $day['date']->isToday() ? 'bg-vanniyan-green-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : '' }}">
                                {{ $day['date']->format('j') }}
                            </span>
                            <button wire:click="openBlackoutModal('{{ $day['date']->format('Y-m-d') }}')" class="text-gray-400 hover:text-gray-600">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mt-2 space-y-1">
                            @foreach($day['blackouts'] as $blackout)
                                <div class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 border border-red-200 relative group cursor-pointer">
                                    <div class="font-semibold">{{ $blackout->venue ? $blackout->venue->name : 'All Venues' }}</div>
                                    <div class="truncate">{{ $blackout->reason ?: 'Blocked' }}</div>
                                    <button wire:click.stop="removeBlackout({{ $blackout->id }})" wire:confirm="Are you sure you want to remove this blackout date?" class="absolute top-1 right-1 hidden group-hover:block text-red-600 hover:text-red-900">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach

                            @foreach($day['bookings'] as $booking)
                                <a href="{{ route('admin.bookings.show', $booking->reference) }}" class="block px-2 py-1 text-xs rounded bg-green-100 text-green-800 border border-green-200 hover:bg-green-200 transition-colors">
                                    <div class="font-semibold">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }}</div>
                                    <div class="truncate">{{ $booking->event_title }}</div>
                                    <div class="text-[10px] text-green-600 truncate">{{ optional($booking->venue)->name }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Blackout Modal -->
    @if($showBlackoutModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showBlackoutModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="saveBlackout">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add Blockout Date</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Venue</label>
                                <select wire:model="blackoutVenueId" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm rounded-md">
                                    <option value="">All Venues</option>
                                    @foreach($venues as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Leave blank to block out all venues.</p>
                                @error('blackoutVenueId') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" wire:model="blackoutStartDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('blackoutStartDate') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" wire:model="blackoutEndDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('blackoutEndDate') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Start Time (Optional)</label>
                                    <input type="time" wire:model="blackoutStartTime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('blackoutStartTime') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">End Time (Optional)</label>
                                    <input type="time" wire:model="blackoutEndTime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('blackoutEndTime') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Reason</label>
                                <input type="text" wire:model="blackoutReason" placeholder="e.g., Maintenance, Private Event" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('blackoutReason') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save Blockout
                        </button>
                        <button type="button" wire:click="$set('showBlackoutModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
