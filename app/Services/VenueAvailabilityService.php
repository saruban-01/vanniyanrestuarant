<?php

namespace App\Services;

use App\Models\VenueBooking;
use App\Models\VenueBlackout;
use App\Models\Venue;
use Illuminate\Support\Carbon;

class VenueAvailabilityService
{
    /**
     * Check if a specific venue is available for the given date, time, and guests.
     *
     * @param int $venueId
     * @param string $date (Y-m-d)
     * @param string $startTime (H:i)
     * @param string $endTime (H:i)
     * @param int $guestCount
     * @return array ['available' => bool, 'reason' => string|null]
     */
    public function checkAvailability($venueId, $date, $startTime, $endTime, $guestCount)
    {
        $venue = Venue::find($venueId);
        if (!$venue || !$venue->is_active) {
            return ['available' => false, 'reason' => 'The selected venue is not currently available.'];
        }

        if ($guestCount > $venue->max_capacity) {
            return ['available' => false, 'reason' => "Guest count exceeds the venue's maximum capacity of {$venue->max_capacity}."];
        }

        $eventDate = Carbon::parse($date);
        
        // Settings-based check (e.g. advance notice)
        $minNoticeHours = app(RestaurantSettingsService::class)->get('venue_booking_notice_hours', 48);
        if (now()->addHours($minNoticeHours)->isAfter($eventDate->copy()->setTimeFromTimeString($startTime))) {
            return ['available' => false, 'reason' => "Venue bookings require at least {$minNoticeHours} hours notice."];
        }

        // Check Blackouts
        $blackout = VenueBlackout::where('is_active', true)
            ->where(function ($q) use ($venueId) {
                $q->whereNull('venue_id')->orWhere('venue_id', $venueId);
            })
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                // If it's an all-day blackout
                $q->whereNull('start_time')
                  ->orWhere(function ($q2) use ($startTime, $endTime) {
                      // Or if the times overlap
                      $q2->where('start_time', '<', $endTime)
                         ->where('end_time', '>', $startTime);
                  });
            })->first();

        if ($blackout) {
            return ['available' => false, 'reason' => 'The venue is blocked for this date/time.'];
        }

        // Check overlapping confirmed bookings for this specific venue
        $overlapping = VenueBooking::where('venue_id', $venueId)
            ->where('event_date', $date)
            ->whereIn('status', ['approved', 'confirmed', 'completed']) // exclude cancelled/declined/requested
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })->exists();

        if ($overlapping) {
            return ['available' => false, 'reason' => 'Another confirmed event overlaps with this time slot.'];
        }

        // TODO: Check Vanniyan organized events overlapping (if they share the venue).
        // Since Vanniyan events don't strictly have a 'venue_id' yet in the events table,
        // we could add logic here if they share the same physical space.
        
        return ['available' => true, 'reason' => null];
    }
}
