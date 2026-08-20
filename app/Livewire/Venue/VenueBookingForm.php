<?php

namespace App\Livewire\Venue;

use Livewire\Attributes\Validate;
use Livewire\Component;
use App\Models\Venue;
use App\Models\VenueEventType;
use App\Models\VenueService;
use App\Services\VenueAvailabilityService;
use App\Services\VenueBookingService;
use Illuminate\Support\Carbon;

class VenueBookingForm extends Component
{
    public $venue;
    public $venueId;

    // Form data
    #[Validate('required|date|after_or_equal:today')]
    public $event_date;
    #[Validate('required|date_format:H:i')]
    public $start_time;
    #[Validate('required|integer|min:1|max:12')]
    public $duration = 4;
    public $guest_count = 50;
    public $event_type_id = null;
    public $event_title = '';
    public $customer_name = '';
    public $phone = '';
    public $email = '';
    public $special_request = '';
    public $selected_services = []; // Array of service IDs

    // State
    public $currentStep = 1; 
    public $availabilityMessage = null;
    public $isAvailable = false;

    // Prevent double submission
    public $isSubmitting = false;

    protected $queryString = ['venueId' => ['as' => 'venue']];

    public function mount()
    {
        if ($this->venueId) {
            $this->venue = Venue::where('is_active', true)->findOrFail($this->venueId);
        } else {
            // Default to first available venue if none specified
            $this->venue = Venue::where('is_active', true)->orderBy('sort_order')->first();
            if ($this->venue) {
                $this->venueId = $this->venue->id;
            }
        }

        $this->dispatch('vanniyan-track', [
            'event' => 'venue_booking_started',
            'data' => [
                'venue_id' => $this->venue?->id,
                'venue_name' => $this->venue?->name,
            ],
        ]);
    }

    public function updatedVenueId()
    {
        if ($this->venueId) {
            $this->venue = Venue::where('is_active', true)->find($this->venueId);
        }
    }

    public function updatedStartTime()
    {
        $this->resetValidation('start_time');
    }

    public function updatedDuration()
    {
        $this->resetValidation('duration');
    }

    public function updatedEventDate()
    {
        $this->resetValidation('event_date');
    }

    public function updatedGuestCount()
    {
        $this->resetValidation('guest_count');
    }

    public function updatedCustomerName()
    {
        $this->resetValidation('customer_name');
    }

    public function updatedPhone()
    {
        $this->resetValidation('phone');
    }

    public function updatedEmail()
    {
        $this->resetValidation('email');
    }

    public function updatedEventTitle()
    {
        $this->resetValidation('event_title');
    }

    public function goToStep($step)
    {
        if (empty($this->event_type_id)) {
            $this->event_type_id = null;
        }

        // Validation for each step before proceeding
        if ($step > $this->currentStep) {
            if ($this->currentStep === 1) {
                $this->validate([
                    'event_date' => 'required|date|after_or_equal:today',
                ]);
            } elseif ($this->currentStep === 2) {
                $this->validate([
                    'start_time' => 'required|date_format:H:i',
                    'duration' => 'required|integer|min:1|max:12',
                ]);
            } elseif ($this->currentStep === 3) {
                $this->validate([
                    'guest_count' => 'required|integer|min:1',
                ]);
            } elseif ($this->currentStep === 4) {
                $this->validate([
                    'venueId' => 'required|exists:venues,id',
                    'event_type_id' => 'nullable|exists:venue_event_types,id',
                    'event_title' => 'required_without:event_type_id|string|max:255',
                ]);
            } elseif ($this->currentStep === 5) {
                $this->validate([
                    'customer_name' => 'required|string|max:255',
                    'phone' => 'required|string|max:20',
                    'email' => 'nullable|email|max:255',
                    'special_request' => 'nullable|string',
                ]);
                
                // Now that we have venue and details, check availability before showing review
                $this->checkAvailability();
            }
        }

        $this->currentStep = $step;
    }

    private function checkAvailability()
    {
        $availabilityService = app(VenueAvailabilityService::class);
        
        $endTime = $this->start_time ? Carbon::parse($this->start_time)->addHours((int)$this->duration)->format('H:i') : null;

        $result = $availabilityService->checkAvailability(
            $this->venueId,
            $this->event_date,
            $this->start_time,
            $endTime,
            $this->guest_count
        );

        $this->isAvailable = $result['available'];
        $this->availabilityMessage = $result['reason'];
    }

    public function submitBooking(VenueBookingService $bookingService)
    {
        if ($this->isSubmitting) {
            return;
        }

        if (empty($this->event_type_id)) {
            $this->event_type_id = null;
        }

        // Final validation
        $this->validate([
            'venueId' => 'required|exists:venues,id',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer',
            'event_type_id' => 'nullable|exists:venue_event_types,id',
            'event_title' => 'required_without:event_type_id|string|max:255',
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $this->isSubmitting = true;
        $this->currentStep = 7; // Show processing state

        // Extract truthy values from the checkbox array
        $serviceIds = array_keys(array_filter($this->selected_services));

        $endTime = $this->start_time ? Carbon::parse($this->start_time)->addHours((int)$this->duration)->format('H:i') : null;

        // If an event type was selected but no custom title, use the type name
        if (empty($this->event_title) && $this->event_type_id) {
            $this->event_title = VenueEventType::find($this->event_type_id)?->name ?? '';
        }

        $endTime = $this->start_time ? Carbon::parse($this->start_time)->addHours((int)$this->duration)->format('H:i') : null;

        $booking = $bookingService->createRequest([
            'venue_id' => $this->venueId,
            'event_type_id' => $this->event_type_id ?: null,
            'event_title' => $this->event_title,
            'event_date' => $this->event_date,
            'start_time' => $this->start_time,
            'end_time' => $endTime,
            'guest_count' => $this->guest_count,
            'customer_name' => $this->customer_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'special_request' => $this->special_request,
        ], $serviceIds);

        session(['venue_booking_just_submitted' => $booking->reference]);

        return redirect()->route('venue.status', ['reference' => $booking->reference, 'token' => $booking->secure_token]);
    }

    public function render()
    {
        return view('livewire.venue.venue-booking-form', [
            'venues' => Venue::where('is_active', true)->orderBy('sort_order')->get(),
            'eventTypes' => VenueEventType::where('is_active', true)->orderBy('sort_order')->get(),
            'services' => VenueService::where('is_available', true)->orderBy('sort_order')->get(),
        ])->layout('components.layouts.app');
    }
}
