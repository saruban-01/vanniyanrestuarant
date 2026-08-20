<?php

namespace App\Livewire\Venue;

use Livewire\Component;
use App\Models\VenueBooking;
use App\Services\AnalyticsService;

class VenueBookingStatus extends Component
{
    public $booking;
    public $reference;
    public $token;

    public function mount($reference)
    {
        $this->reference = $reference;
        $this->token = request()->query('token');

        $this->booking = VenueBooking::with(['venue', 'eventType', 'services'])
            ->where('reference', $this->reference)->firstOrFail();

        // Validate secure token
        if ($this->booking->secure_token !== $this->token) {
            abort(403, 'Unauthorized access to booking details.');
        }

        $analytics = app(AnalyticsService::class);

        // venue_booking_submitted fires once, on the first visit right after submission.
        if (session()->pull('venue_booking_just_submitted') === $this->booking->reference) {
            $this->dispatch('vanniyan-track', [
                'event' => 'venue_booking_submitted',
                'data' => [
                    'booking_id' => $this->booking->reference,
                    'venue_id' => $this->booking->venue_id,
                    'venue_name' => $this->booking->venue?->name,
                    'event_date' => $this->booking->event_date,
                    'guest_count' => $this->booking->guest_count,
                ],
            ]);
        }

        // venue_booking_confirmed fires only once the admin has approved the
        // booking and the customer views it (server-authoritative one-shot).
        if ($this->booking->status === 'approved' && ! $analytics->confirmedSent($this->booking)) {
            $analytics->markConfirmedSent($this->booking);

            $this->dispatch('vanniyan-track', [
                'event' => 'venue_booking_confirmed',
                'data' => [
                    'booking_id' => $this->booking->reference,
                    'venue_id' => $this->booking->venue_id,
                    'venue_name' => $this->booking->venue?->name,
                    'event_date' => $this->booking->event_date,
                    'guest_count' => $this->booking->guest_count,
                ],
                'consent' => 'marketing',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.venue.venue-booking-status')->layout('components.layouts.app');
    }
}
