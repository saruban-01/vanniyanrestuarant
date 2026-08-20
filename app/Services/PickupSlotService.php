<?php

namespace App\Services;

use App\Models\BusinessHour;
use App\Models\SpecialHour;
use Carbon\Carbon;

class PickupSlotService
{
    /**
     * Generate available pickup slots within the restaurant's opening hours.
     * Returns an empty array when the restaurant is closed.
     */
    public function getAvailableSlots(): array
    {
        $now = Carbon::now('Asia/Colombo');

        $hours = $this->getTodayHours($now);
        if (!$hours) {
            return [];
        }

        [$openTime, $closeTime] = $hours;

        // Earliest slot: 30 mins from now (rounded up to the next 15 min)
        $start = $now->copy()->addMinutes(30)->ceilMinute(15);
        // No slots after the kitchen's last pickup (15 mins before closing)
        $end = $closeTime->copy()->subMinutes(15)->floorMinute(15);

        // Still open but fewer than 3 hours remain — clamp to the remaining window
        if ($start > $end) {
            return [];
        }

        $end = $start->copy()->addHours(3)->min($end);

        $slots = [];
        while ($start <= $end) {
            $slots[] = [
                'value' => $start->format('Y-m-d H:i:s'),
                'label' => $start->format('g:i A'),
            ];
            $start->addMinutes(15);
        }

        return $slots;
    }

    /**
     * Get today's open/close times, honouring special hours overrides.
     *
     * @return array{0: Carbon, 1: Carbon}|null null when closed today
     */
    protected function getTodayHours(Carbon $now): ?array
    {
        $date = $now->format('Y-m-d');

        $specialHour = SpecialHour::where('date', $date)->first();
        if ($specialHour) {
            if ($specialHour->is_closed) {
                return null;
            }
            return [
                Carbon::parse($date . ' ' . $specialHour->open_time, 'Asia/Colombo'),
                Carbon::parse($date . ' ' . $specialHour->close_time, 'Asia/Colombo'),
            ];
        }

        $businessHour = BusinessHour::where('day_of_week', $now->dayOfWeek)->first();
        if (!$businessHour || $businessHour->is_closed) {
            return null;
        }

        return [
            Carbon::parse($date . ' ' . $businessHour->open_time, 'Asia/Colombo'),
            Carbon::parse($date . ' ' . $businessHour->close_time, 'Asia/Colombo'),
        ];
    }
}