<?php

namespace App\Livewire\Admin\Venues;

use Livewire\Component;
use App\Models\VenueBooking;
use App\Models\VenueBlackout;
use Illuminate\Support\Carbon;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    
    // For new blackouts
    public $showBlackoutModal = false;
    public $blackoutVenueId = null;
    public $blackoutStartDate;
    public $blackoutEndDate;
    public $blackoutStartTime;
    public $blackoutEndTime;
    public $blackoutReason;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function openBlackoutModal($date = null)
    {
        $this->resetValidation();
        $this->blackoutStartDate = $date ?? now()->format('Y-m-d');
        $this->blackoutEndDate = $date ?? now()->format('Y-m-d');
        $this->blackoutStartTime = null;
        $this->blackoutEndTime = null;
        $this->blackoutReason = '';
        $this->blackoutVenueId = null;
        $this->showBlackoutModal = true;
    }

    public function saveBlackout()
    {
        $this->validate([
            'blackoutStartDate' => 'required|date',
            'blackoutEndDate' => 'required|date|after_or_equal:blackoutStartDate',
            'blackoutStartTime' => 'nullable|date_format:H:i',
            'blackoutEndTime' => 'nullable|date_format:H:i|after:blackoutStartTime',
            'blackoutVenueId' => 'nullable|exists:venues,id',
            'blackoutReason' => 'nullable|string|max:255',
        ]);

        VenueBlackout::create([
            'venue_id' => $this->blackoutVenueId ?: null,
            'start_date' => $this->blackoutStartDate,
            'end_date' => $this->blackoutEndDate,
            'start_time' => $this->blackoutStartTime,
            'end_time' => $this->blackoutEndTime,
            'reason' => $this->blackoutReason,
            'is_active' => true,
        ]);

        $this->showBlackoutModal = false;
        $this->dispatch('notify', message: 'Blackout dates saved.', type: 'success');
    }

    public function removeBlackout($id)
    {
        VenueBlackout::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Blackout removed.', type: 'success');
    }

    public function render()
    {
        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1);
        $endDate = $startDate->copy()->endOfMonth();

        $bookings = VenueBooking::with('venue')
            ->whereBetween('event_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->whereIn('status', ['approved', 'confirmed', 'completed'])
            ->get();

        $blackouts = VenueBlackout::with('venue')
            ->where('is_active', true)
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate->format('Y-m-d'))
                         ->where('end_date', '>=', $endDate->format('Y-m-d'));
                  });
            })->get();

        $calendar = [];
        $currentDate = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
        $calendarEndDate = $endDate->copy()->endOfWeek(Carbon::SATURDAY);

        while ($currentDate <= $calendarEndDate) {
            $dateString = $currentDate->format('Y-m-d');
            $dayBookings = $bookings->where('event_date', clone $currentDate)->values(); // cast to cloned obj just in case
            
            $dayBlackouts = $blackouts->filter(function ($b) use ($dateString) {
                return $dateString >= $b->start_date->format('Y-m-d') && $dateString <= $b->end_date->format('Y-m-d');
            })->values();

            $calendar[] = [
                'date' => $currentDate->copy(),
                'isCurrentMonth' => $currentDate->month === $this->currentMonth,
                'bookings' => $dayBookings,
                'blackouts' => $dayBlackouts,
            ];
            $currentDate->addDay();
        }

        return view('livewire.admin.venues.calendar', [
            'calendar' => $calendar,
            'venues' => \App\Models\Venue::where('is_active', true)->get(),
        ])->layout('components.layouts.admin');
    }
}
