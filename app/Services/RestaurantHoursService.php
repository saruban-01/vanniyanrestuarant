<?php

namespace App\Services;

use App\Models\BusinessHour;
use App\Models\SpecialHour;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class RestaurantHoursService
{
    /**
     * Determine if the restaurant is currently open.
     */
    public function isOpenNow(): bool
    {
        $now = Carbon::now('Asia/Colombo');
        $date = $now->format('Y-m-d');
        $time = $now->format('H:i:s');
        $dayOfWeek = $now->dayOfWeek;

        // 1. Check Special Hours first
        $specialHour = SpecialHour::where('date', $date)->first();
        if ($specialHour) {
            if ($specialHour->is_closed) return false;
            return $time >= $specialHour->open_time && $time <= $specialHour->close_time;
        }

        // 2. Check regular business hours
        $businessHour = BusinessHour::where('day_of_week', $dayOfWeek)->first();
        if (!$businessHour || $businessHour->is_closed) return false;
        
        return $time >= $businessHour->open_time && $time <= $businessHour->close_time;
    }

    /**
     * Get the next opening time if currently closed.
     */
    public function getNextOpeningTime(): ?string
    {
        if ($this->isOpenNow()) return null;

        $now = Carbon::now('Asia/Colombo');
        
        // Check next 7 days
        for ($i = 0; $i <= 7; $i++) {
            $checkDate = $now->copy()->addDays($i);
            $dateStr = $checkDate->format('Y-m-d');
            $dayOfWeek = $checkDate->dayOfWeek;

            // Check Special
            $specialHour = SpecialHour::where('date', $dateStr)->first();
            if ($specialHour && !$specialHour->is_closed) {
                $openTime = Carbon::parse($dateStr . ' ' . $specialHour->open_time, 'Asia/Colombo');
                if ($openTime->gt($now)) {
                    return $this->formatNextOpenTime($openTime, $now);
                }
            } elseif (!$specialHour) {
                // Check Regular
                $businessHour = BusinessHour::where('day_of_week', $dayOfWeek)->first();
                if ($businessHour && !$businessHour->is_closed) {
                    $openTime = Carbon::parse($dateStr . ' ' . $businessHour->open_time, 'Asia/Colombo');
                    if ($openTime->gt($now)) {
                        return $this->formatNextOpenTime($openTime, $now);
                    }
                }
            }
        }
        
        return null;
    }

    protected function formatNextOpenTime(Carbon $openTime, Carbon $now): string
    {
        if ($openTime->isToday()) {
            return 'Today at ' . $openTime->format('g:i A');
        } elseif ($openTime->isTomorrow()) {
            return 'Tomorrow at ' . $openTime->format('g:i A');
        } else {
            return $openTime->format('l \a\t g:i A'); // e.g. "Tuesday at 11:00 AM"
        }
    }

    /**
     * Get structured schedule for the week.
     */
    public function getWeeklySchedule(): array
    {
        return Cache::remember('weekly_schedule', 3600, function () {
            $hours = BusinessHour::orderBy('day_of_week')->get();
            $schedule = [];
            
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            
            foreach ($hours as $hour) {
                $dayName = $days[$hour->day_of_week];
                if ($hour->is_closed) {
                    $schedule[$dayName] = 'Closed';
                } else {
                    $open = Carbon::parse($hour->open_time)->format('g:i A');
                    $close = Carbon::parse($hour->close_time)->format('g:i A');
                    $schedule[$dayName] = $open . ' – ' . $close;
                }
            }
            
            return $schedule;
        });
    }
}
