<?php

namespace App\Livewire\Pages;

use App\Services\ReservationAvailabilityService;
use App\Services\ReservationService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class ReservationPage extends Component
{
    public int $step = 1;
    
    // Booking Data
    public string $date = '';
    public string $time = '';
    public int $guests = 2;
    
    // Customer Details
    public string $customer_name = '';
    public string $phone = '';
    public string $email = '';
    public string $special_request = '';

    // State
    public array $availableSlots = [];
    public ?string $errorMessage = null;
    public bool $isSubmitting = false;
    
    // Result
    public ?string $reservationReference = null;

    protected $listeners = ['dateSelected' => 'setDate'];

    public function mount()
    {
        // Default to tomorrow if empty
        $this->date = Carbon::tomorrow('Asia/Colombo')->format('Y-m-d');
        $this->fetchAvailableSlots();

        // booking_confirmed fires only when the customer re-visits the booking
        // page and their reservation has been confirmed by the admin (one-shot).
        if (session()->has('last_reservation_reference')) {
            $reservation = \App\Models\Reservation::where('reservation_reference', session('last_reservation_reference'))->first();

            if ($reservation && $reservation->status === 'confirmed' && ! app(\App\Services\AnalyticsService::class)->confirmedSent($reservation)) {
                app(\App\Services\AnalyticsService::class)->markConfirmedSent($reservation);

                $this->dispatch('vanniyan-track', [
                    'event' => 'booking_confirmed',
                    'data' => [
                        'booking_id' => $reservation->reservation_reference,
                        'date' => $reservation->reservation_date,
                        'time' => $reservation->reservation_time,
                        'guests' => $reservation->guests,
                    ],
                    'consent' => 'marketing',
                ]);
            }
        }

        $this->dispatch('vanniyan-track', [
            'event' => 'booking_started',
            'data' => [
                'guests' => $this->guests,
            ],
        ]);
    }

    public function setDate($date)
    {
        $this->date = $date;
        $this->time = ''; // Reset time when date changes
        $this->fetchAvailableSlots();
    }

    public function updatedGuests()
    {
        $this->fetchAvailableSlots();
        // Keep the selected time only if it is still available for the new guest count
        if ($this->time && !collect($this->availableSlots)->contains('time', $this->time)) {
            $this->time = '';
        }
    }

    public function fetchAvailableSlots()
    {
        $this->errorMessage = null;
        if (!$this->date || $this->guests < 1) {
            $this->availableSlots = [];
            return;
        }

        $service = app(ReservationAvailabilityService::class);
        $this->availableSlots = $service->getAvailableSlots($this->date, $this->guests);
    }

    public function selectTime($time)
    {
        $this->time = $time;
        $this->goToStep(3); // Go to Guests (or jump to 4 Details if Guests already verified)
    }

    public function goToStep(int $step)
    {
        $this->errorMessage = null;
        
        // Validation before moving forward
        if ($step > 1 && !$this->date) {
            $this->errorMessage = "Please select a date.";
            return;
        }
        if ($step > 2 && !$this->time) {
            $this->errorMessage = "Please select a time.";
            return;
        }
        if ($step > 3 && ($this->guests < 1 || $this->guests > 10)) {
            $this->errorMessage = "Please select between 1 and 10 guests.";
            return;
        }
        if ($step > 4) {
            $this->validate([
                'customer_name' => 'required|min:2',
                'phone' => 'required|min:9',
                'email' => 'nullable|email',
            ], [
                'customer_name.required' => 'Please enter your full name.',
                'phone.required' => 'Please enter a valid mobile number.',
            ]);
        }

        $this->step = $step;
    }

    public function confirmReservation(ReservationService $reservationService)
    {
        $this->errorMessage = null;
        $this->isSubmitting = true;

        try {
            // Generate idempotency key for this specific attempt
            $idempotencyKey = session()->getId() . '_' . md5(json_encode([
                $this->date, $this->time, $this->guests, $this->customer_name, $this->phone
            ]));

            $data = [
                'reservation_date' => $this->date,
                'reservation_time' => $this->time,
                'guests' => $this->guests,
                'customer_name' => $this->customer_name,
                'phone' => $this->phone,
                'email' => $this->email,
                'special_request' => $this->special_request,
            ];

            $reservation = $reservationService->createReservation($data, $idempotencyKey);
            
            $this->reservationReference = $reservation->reservation_reference;
            $this->step = 6; // Success step

            session(['last_reservation_reference' => $reservation->reservation_reference]);

            $this->dispatch('vanniyan-track', [
                'event' => 'booking_submitted',
                'data' => [
                    'booking_id' => $reservation->reservation_reference,
                    'date' => $reservation->reservation_date,
                    'time' => $reservation->reservation_time,
                    'guests' => $reservation->guests,
                ],
            ]);
            
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            // If it's a conflict, maybe fetch new slots
            $this->fetchAvailableSlots();
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        $settings = app(\App\Services\RestaurantSettingsService::class)->getAll();

        return view('livewire.pages.reservation-page', [
            'settings' => $settings,
        ])->layout('components.layouts.app', [
            'title' => 'Reserve a Table at Vanniyan Restaurant | Kilinochchi',
            'meta_description' => 'Reserve your table at Vanniyan Restaurant in Kilinochchi, Sri Lanka. Choose your date, time and number of guests for your Vanniyan dining experience.',
        ]);
    }
}
