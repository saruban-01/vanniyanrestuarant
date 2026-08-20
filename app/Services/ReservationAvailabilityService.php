<?php

namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;

class ReservationAvailabilityService
{
    /**
     * Define the basic slots (in production, this might come from settings).
     */
    protected array $baseSlots = [
        '11:30:00', '12:00:00', '12:30:00', '13:00:00',
        '18:00:00', '18:30:00', '19:00:00', '19:30:00', '20:00:00', '20:30:00'
    ];

    /**
     * Fetch available slots for a given date and guest count.
     */
    public function getAvailableSlots(string $dateString, int $guests, int $durationMinutes = 90): array
    {
        $date = Carbon::parse($dateString, 'Asia/Colombo')->startOfDay();
        $now = Carbon::now('Asia/Colombo');
        
        // 1. Validate Booking Horizon (e.g. 30 days max)
        if ($date->gt($now->copy()->addDays(30))) {
            return []; // Outside horizon
        }
        
        if ($date->lt($now->copy()->startOfDay())) {
            return []; // Past dates
        }

        $availableSlots = [];
        
        // Check each base slot
        foreach ($this->baseSlots as $timeString) {
            $slotTime = Carbon::parse($dateString . ' ' . $timeString, 'Asia/Colombo');
            
            // 2. Minimum Notice (e.g. 120 mins)
            if ($slotTime->lt($now->copy()->addMinutes(120))) {
                continue; // Too soon or in the past
            }
            
            // 3. Table Availability Check
            $tableService = app(TableAllocationService::class);
            $availableTable = $tableService->findAvailableTable($dateString, $timeString, $guests, $durationMinutes);
            
            if ($availableTable) {
                $availableSlots[] = [
                    'time' => Carbon::parse($timeString)->format('H:i'), // e.g. "19:30"
                    'display_time' => Carbon::parse($timeString)->format('g:i A'), // e.g. "7:30 PM"
                    'available' => true,
                ];
            }
        }
        
        return $availableSlots;
    }
}
