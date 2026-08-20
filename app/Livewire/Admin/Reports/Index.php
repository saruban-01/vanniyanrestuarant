<?php

namespace App\Livewire\Admin\Reports;

use App\Models\TakeawayOrder;
use App\Models\Reservation;
use App\Models\VenueBooking;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public $dateRange = '7days'; // today, 7days, 30days, custom
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->setDateRange('7days');
    }

    public function setDateRange($range)
    {
        $this->dateRange = $range;
        
        $today = Carbon::today();
        
        switch ($range) {
            case 'today':
                $this->startDate = $today->copy()->format('Y-m-d');
                $this->endDate = $today->copy()->format('Y-m-d');
                break;
            case '7days':
                $this->startDate = $today->copy()->subDays(6)->format('Y-m-d');
                $this->endDate = $today->copy()->format('Y-m-d');
                break;
            case '30days':
                $this->startDate = $today->copy()->subDays(29)->format('Y-m-d');
                $this->endDate = $today->copy()->format('Y-m-d');
                break;
        }
    }

    public function getReportData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Takeaway Data
        $takeawayOrders = TakeawayOrder::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'confirmed', 'received']); // Count valid orders
            
        $takeawaySales = (clone $takeawayOrders)->where('status', 'completed')->sum('total');
        $takeawayCount = (clone $takeawayOrders)->count();

        // Reservation Data
        $reservations = Reservation::whereBetween('reservation_date', [$start, $end])
            ->whereIn('status', ['confirmed', 'completed']);
            
        $reservationCount = (clone $reservations)->count();
        $reservationGuests = (clone $reservations)->sum('guests');

        // Venue Booking Data
        $venueBookings = VenueBooking::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['confirmed', 'completed']);
            
        $eventSales = 0; // Not tracked online
        $eventGuestCount = (clone $venueBookings)->sum('guest_count');

        // Total Revenue
        $totalRevenue = $takeawaySales + $eventSales;

        // Daily breakdown for charts
        $dailyTakeaway = TakeawayOrder::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        // Venue booking sales are offline, so chart is 0
        $dailyEvents = [];

        // Merge daily dates
        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end);
        $chartData = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $chartData[] = [
                'date' => $date->format('M d'),
                'takeaway' => $dailyTakeaway[$dateStr] ?? 0,
                'events' => $dailyEvents[$dateStr] ?? 0,
            ];
        }
        
        // Add last day
        $dateStr = $end->format('Y-m-d');
        $chartData[] = [
            'date' => $end->format('M d'),
            'takeaway' => $dailyTakeaway[$dateStr] ?? 0,
            'events' => $dailyEvents[$dateStr] ?? 0,
        ];

        return [
            'totalRevenue' => $totalRevenue,
            'takeawaySales' => $takeawaySales,
            'takeawayCount' => $takeawayCount,
            'reservationCount' => $reservationCount,
            'reservationGuests' => $reservationGuests,
            'eventSales' => $eventSales,
            'eventGuestCount' => $eventGuestCount,
            'chartData' => $chartData,
        ];
    }

    public function render()
    {
        return view('livewire.admin.reports.index', [
            'data' => $this->getReportData()
        ])->title('Analytics & Reports - Vanniyan Admin');
    }
}
