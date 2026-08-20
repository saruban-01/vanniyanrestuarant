<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TableAllocationService
{
    /**
     * Find the best available table for the requested date, time, and guests.
     * Strategy: Smallest sufficient table first.
     */
    public function findAvailableTable(string $date, string $time, int $guests, int $durationMinutes = 90): ?RestaurantTable
    {
        // Find tables that are active and can fit the guests
        $capableTables = RestaurantTable::where('is_active', true)
            ->where('capacity', '>=', $guests)
            ->orderBy('capacity', 'asc') // Prefer smallest fit
            ->get();
            
        if ($capableTables->isEmpty()) {
            return null; // Group too large or no active tables
        }

        // Calculate overlap window
        $requestedStart = Carbon::parse($date . ' ' . $time, 'Asia/Colombo');
        $requestedEnd = $requestedStart->copy()->addMinutes($durationMinutes);

        // Fetch overlapping reservations for the day
        // Overlap condition: (existingStart < requestedEnd) AND (existingEnd > requestedStart)
        // Note: For simplicity, we fetch all reservations for the day and filter in memory, 
        // but DB querying is also fine.
        $dayReservations = Reservation::where('reservation_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        foreach ($capableTables as $table) {
            $isAvailable = true;

            foreach ($dayReservations as $reservation) {
                if ($reservation->table_id === $table->id) {
                    $existingStart = Carbon::parse($reservation->reservation_date . ' ' . $reservation->reservation_time, 'Asia/Colombo');
                    $existingEnd = $existingStart->copy()->addMinutes($reservation->duration_minutes);
                    
                    if ($existingStart->lt($requestedEnd) && $existingEnd->gt($requestedStart)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if ($isAvailable) {
                return $table; // Found the best table
            }
        }

        return null; // All capable tables are booked
    }
}
