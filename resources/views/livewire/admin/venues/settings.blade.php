<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Venue Settings</h1>
            <p class="mt-2 text-sm text-gray-700">Manage global settings, venues, event types, and services available for customers to book.</p>
        </div>
    </div>

    <!-- Global Settings -->
    <div class="bg-white shadow sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Global Venue Constraints</h3>
            <form wire:submit="saveGlobalSettings">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 flex items-center">
                        <input wire:model="venue_booking_enabled" type="checkbox" class="h-4 w-4 text-vanniyan-green-600 focus:ring-vanniyan-green-500 border-gray-300 rounded">
                        <label class="ml-2 block text-sm font-medium text-gray-900">
                            Enable Venue Booking System
                        </label>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Minimum Guests (Global)</label>
                        <div class="mt-1">
                            <input type="number" wire:model="venue_min_guests" class="shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Maximum Guests (Global)</label>
                        <div class="mt-1">
                            <input type="number" wire:model="venue_max_guests" class="shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Min. Notice (Hours)</label>
                        <div class="mt-1">
                            <input type="number" wire:model="venue_booking_notice_hours" class="shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-vanniyan-green-600 hover:bg-vanniyan-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-500 sm:text-sm">
                        Save Global Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Venues -->
    <div class="bg-white shadow sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Venues</h3>
            <button wire:click="openVenueModal" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-vanniyan-green-700 bg-vanniyan-green-100 hover:bg-vanniyan-green-200">
                Add Venue
            </button>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($venues as $venue)
            <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $venue->name }} (Cap: {{ $venue->max_capacity }})</h4>
                    <p class="text-sm text-gray-500">{{ $venue->description }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button wire:click="toggleVenue({{ $venue->id }})" class="text-sm {{ $venue->is_active ? 'text-green-600 hover:text-green-900' : 'text-gray-400 hover:text-gray-600' }}">
                        {{ $venue->is_active ? 'Active' : 'Disabled' }}
                    </button>
                    <button wire:click="openVenueModal({{ $venue->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                </div>
            </li>
            @empty
            <li class="px-4 py-4 text-center text-sm text-gray-500">No venues configured.</li>
            @endforelse
        </ul>
    </div>

    <!-- Event Types -->
    <div class="bg-white shadow sm:rounded-lg mb-8">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Event Types</h3>
            <button wire:click="openEventTypeModal" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-vanniyan-green-700 bg-vanniyan-green-100 hover:bg-vanniyan-green-200">
                Add Event Type
            </button>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($eventTypes as $type)
            <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $type->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $type->description }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button wire:click="toggleEventType({{ $type->id }})" class="text-sm {{ $type->is_active ? 'text-green-600 hover:text-green-900' : 'text-gray-400 hover:text-gray-600' }}">
                        {{ $type->is_active ? 'Active' : 'Disabled' }}
                    </button>
                    <button wire:click="openEventTypeModal({{ $type->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                </div>
            </li>
            @empty
            <li class="px-4 py-4 text-center text-sm text-gray-500">No event types configured.</li>
            @endforelse
        </ul>
    </div>

    <!-- Services -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Venue Services</h3>
            <button wire:click="openServiceModal" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-vanniyan-green-700 bg-vanniyan-green-100 hover:bg-vanniyan-green-200">
                Add Service
            </button>
        </div>
        <ul class="divide-y divide-gray-200">
            @forelse($services as $service)
            <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ $service->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $service->description }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        {{ ucfirst(str_replace('_', ' ', $service->price_type)) }}
                        @if(in_array($service->price_type, ['fixed', 'per_guest']))
                            (LKR {{ number_format($service->base_price) }})
                        @endif
                    </span>
                    <button wire:click="toggleService({{ $service->id }})" class="text-sm {{ $service->is_available ? 'text-green-600 hover:text-green-900' : 'text-gray-400 hover:text-gray-600' }}">
                        {{ $service->is_available ? 'Available' : 'Disabled' }}
                    </button>
                    <button wire:click="openServiceModal({{ $service->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm">Edit</button>
                </div>
            </li>
            @empty
            <li class="px-4 py-4 text-center text-sm text-gray-500">No services configured.</li>
            @endforelse
        </ul>
    </div>

    <!-- Venue Modal -->
    @if($showVenueModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showVenueModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="saveVenue">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ $venueId ? 'Edit Venue' : 'Add Venue' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" wire:model="venueName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('venueName') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="venueDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm"></textarea>
                                @error('venueDescription') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Max Capacity</label>
                                    <input type="number" wire:model="venueCapacity" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('venueCapacity') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                    <input type="number" wire:model="venueSortOrder" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('venueSortOrder') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input wire:model="venueIsActive" type="checkbox" class="h-4 w-4 text-vanniyan-green-600 focus:ring-vanniyan-green-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-vanniyan-green-600 text-base font-medium text-white hover:bg-vanniyan-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" wire:click="$set('showVenueModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Event Type Modal -->
    @if($showEventTypeModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showEventTypeModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="saveEventType">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ $eventTypeId ? 'Edit Event Type' : 'Add Event Type' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" wire:model="eventTypeName" placeholder="e.g., Birthday" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('eventTypeName') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="eventTypeDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm"></textarea>
                                @error('eventTypeDescription') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                <input type="number" wire:model="eventTypeSortOrder" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('eventTypeSortOrder') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center">
                                <input wire:model="eventTypeIsActive" type="checkbox" class="h-4 w-4 text-vanniyan-green-600 focus:ring-vanniyan-green-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-vanniyan-green-600 text-base font-medium text-white hover:bg-vanniyan-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" wire:click="$set('showEventTypeModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Service Modal -->
    @if($showServiceModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showServiceModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="saveService">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ $serviceId ? 'Edit Service' : 'Add Service' }}</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Service Name</label>
                                <input type="text" wire:model="serviceName" placeholder="e.g., Venue Only, Catering" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('serviceName') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea wire:model="serviceDescription" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm"></textarea>
                                @error('serviceDescription') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Price Type</label>
                                    <select wire:model="servicePriceType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm rounded-md">
                                        <option value="fixed">Fixed Price</option>
                                        <option value="per_guest">Per Guest</option>
                                        <option value="quote">Quote Required</option>
                                        <option value="included">Included (Free)</option>
                                    </select>
                                    @error('servicePriceType') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Base Price (LKR)</label>
                                    <input type="number" step="0.01" wire:model="serviceBasePrice" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                    @error('serviceBasePrice') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sort Order</label>
                                <input type="number" wire:model="serviceSortOrder" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-vanniyan-green-500 focus:border-vanniyan-green-500 sm:text-sm">
                                @error('serviceSortOrder') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center">
                                <input wire:model="serviceIsAvailable" type="checkbox" class="h-4 w-4 text-vanniyan-green-600 focus:ring-vanniyan-green-500 border-gray-300 rounded">
                                <label class="ml-2 block text-sm text-gray-900">Available</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-vanniyan-green-600 text-base font-medium text-white hover:bg-vanniyan-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" wire:click="$set('showServiceModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
