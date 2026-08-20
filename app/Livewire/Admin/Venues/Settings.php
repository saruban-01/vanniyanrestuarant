<?php

namespace App\Livewire\Admin\Venues;

use Livewire\Component;
use App\Models\Venue;
use App\Models\VenueEventType;
use App\Models\VenueService;
use App\Services\RestaurantSettingsService;

class Settings extends Component
{
    // Settings state
    public $venue_booking_enabled;
    public $venue_min_guests;
    public $venue_max_guests;
    public $venue_booking_notice_hours;
    public $venue_default_duration;

    // Modals state
    public $showVenueModal = false;
    public $showEventTypeModal = false;
    public $showServiceModal = false;

    // Venue Form
    public $venueId;
    public $venueName;
    public $venueDescription;
    public $venueCapacity;
    public $venueLocation;
    public $venueIsActive = true;
    public $venueSortOrder = 0;

    // Event Type Form
    public $eventTypeId;
    public $eventTypeName;
    public $eventTypeDescription;
    public $eventTypeIsActive = true;
    public $eventTypeSortOrder = 0;

    // Service Form
    public $serviceId;
    public $serviceName;
    public $serviceDescription;
    public $servicePriceType = 'fixed';
    public $serviceBasePrice = 0;
    public $serviceIsAvailable = true;
    public $serviceSortOrder = 0;

    public function mount(RestaurantSettingsService $settingsService)
    {
        $this->venue_booking_enabled = $settingsService->get('venue_booking_enabled', '1');
        $this->venue_min_guests = $settingsService->get('venue_min_guests', '10');
        $this->venue_max_guests = $settingsService->get('venue_max_guests', '200');
        $this->venue_booking_notice_hours = $settingsService->get('venue_booking_notice_hours', '48');
        $this->venue_default_duration = $settingsService->get('venue_default_duration', '240');
    }

    public function saveGlobalSettings(RestaurantSettingsService $settingsService)
    {
        $settingsService->setMany([
            'venue_booking_enabled' => $this->venue_booking_enabled ? '1' : '0',
            'venue_min_guests' => $this->venue_min_guests,
            'venue_max_guests' => $this->venue_max_guests,
            'venue_booking_notice_hours' => $this->venue_booking_notice_hours,
            'venue_default_duration' => $this->venue_default_duration,
        ]);
        
        // Settings are cached, so we might need to clear it or let the service handle it
        cache()->forget('restaurant_settings');

        $this->dispatch('notify', message: 'Global settings updated.', type: 'success');
    }

    // --- Venues ---
    public function openVenueModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $venue = Venue::findOrFail($id);
            $this->venueId = $venue->id;
            $this->venueName = $venue->name;
            $this->venueDescription = $venue->description;
            $this->venueCapacity = $venue->max_capacity;
            $this->venueLocation = $venue->location;
            $this->venueIsActive = $venue->is_active;
            $this->venueSortOrder = $venue->sort_order;
        } else {
            $this->venueId = null;
            $this->venueName = '';
            $this->venueDescription = '';
            $this->venueCapacity = 50;
            $this->venueLocation = '';
            $this->venueIsActive = true;
            $this->venueSortOrder = 0;
        }
        $this->showVenueModal = true;
    }

    public function saveVenue()
    {
        $this->validate([
            'venueName' => 'required|string|max:255',
            'venueDescription' => 'nullable|string',
            'venueCapacity' => 'required|integer|min:1',
            'venueLocation' => 'nullable|string',
            'venueSortOrder' => 'required|integer',
        ]);

        Venue::updateOrCreate(
            ['id' => $this->venueId],
            [
                'name' => $this->venueName,
                'description' => $this->venueDescription,
                'max_capacity' => $this->venueCapacity,
                'location' => $this->venueLocation,
                'is_active' => $this->venueIsActive,
                'sort_order' => $this->venueSortOrder,
            ]
        );

        $this->showVenueModal = false;
        $this->dispatch('notify', message: 'Venue saved.', type: 'success');
    }

    public function toggleVenue($id)
    {
        $venue = Venue::findOrFail($id);
        $venue->update(['is_active' => !$venue->is_active]);
    }

    // --- Event Types ---
    public function openEventTypeModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $type = VenueEventType::findOrFail($id);
            $this->eventTypeId = $type->id;
            $this->eventTypeName = $type->name;
            $this->eventTypeDescription = $type->description;
            $this->eventTypeIsActive = $type->is_active;
            $this->eventTypeSortOrder = $type->sort_order;
        } else {
            $this->eventTypeId = null;
            $this->eventTypeName = '';
            $this->eventTypeDescription = '';
            $this->eventTypeIsActive = true;
            $this->eventTypeSortOrder = 0;
        }
        $this->showEventTypeModal = true;
    }

    public function saveEventType()
    {
        $this->validate([
            'eventTypeName' => 'required|string|max:255',
            'eventTypeDescription' => 'nullable|string',
            'eventTypeSortOrder' => 'required|integer',
        ]);

        VenueEventType::updateOrCreate(
            ['id' => $this->eventTypeId],
            [
                'name' => $this->eventTypeName,
                'description' => $this->eventTypeDescription,
                'is_active' => $this->eventTypeIsActive,
                'sort_order' => $this->eventTypeSortOrder,
            ]
        );

        $this->showEventTypeModal = false;
        $this->dispatch('notify', message: 'Event type saved.', type: 'success');
    }

    public function toggleEventType($id)
    {
        $type = VenueEventType::findOrFail($id);
        $type->update(['is_active' => !$type->is_active]);
    }

    // --- Services ---
    public function openServiceModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $service = VenueService::findOrFail($id);
            $this->serviceId = $service->id;
            $this->serviceName = $service->name;
            $this->serviceDescription = $service->description;
            $this->servicePriceType = $service->price_type;
            $this->serviceBasePrice = $service->base_price;
            $this->serviceIsAvailable = $service->is_available;
            $this->serviceSortOrder = $service->sort_order;
        } else {
            $this->serviceId = null;
            $this->serviceName = '';
            $this->serviceDescription = '';
            $this->servicePriceType = 'fixed';
            $this->serviceBasePrice = 0;
            $this->serviceIsAvailable = true;
            $this->serviceSortOrder = 0;
        }
        $this->showServiceModal = true;
    }

    public function saveService()
    {
        $this->validate([
            'serviceName' => 'required|string|max:255',
            'serviceDescription' => 'nullable|string',
            'servicePriceType' => 'required|in:fixed,quote,included,per_guest',
            'serviceBasePrice' => 'required|numeric|min:0',
            'serviceSortOrder' => 'required|integer',
        ]);

        VenueService::updateOrCreate(
            ['id' => $this->serviceId],
            [
                'name' => $this->serviceName,
                'description' => $this->serviceDescription,
                'price_type' => $this->servicePriceType,
                'base_price' => $this->serviceBasePrice,
                'is_available' => $this->serviceIsAvailable,
                'sort_order' => $this->serviceSortOrder,
            ]
        );

        $this->showServiceModal = false;
        $this->dispatch('notify', message: 'Service saved.', type: 'success');
    }

    public function toggleService($id)
    {
        $service = VenueService::findOrFail($id);
        $service->update(['is_available' => !$service->is_available]);
    }

    public function render()
    {
        return view('livewire.admin.venues.settings', [
            'venues' => Venue::orderBy('sort_order')->get(),
            'eventTypes' => VenueEventType::orderBy('sort_order')->get(),
            'services' => VenueService::orderBy('sort_order')->get(),
        ])->layout('components.layouts.admin');
    }
}
