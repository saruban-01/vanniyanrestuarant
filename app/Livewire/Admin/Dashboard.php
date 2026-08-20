<?php

namespace App\Livewire\Admin;

use App\Models\TakeawayOrder;
use App\Models\Reservation;
use App\Models\VenueBooking;
use App\Models\ContactMessage;
use App\Services\RestaurantHoursService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render(RestaurantHoursService $hoursService)
    {
        $today = Carbon::today();

        // KPIs
        $takeawayOrdersCount = TakeawayOrder::whereDate('created_at', $today)->count();
        $reservationsCount = Reservation::whereDate('reservation_date', $today)
                                        ->whereIn('status', ['PENDING', 'CONFIRMED'])
                                        ->count();
        $venueBookingsCount = VenueBooking::whereDate('event_date', '>=', $today)
                                            ->whereIn('status', ['approved', 'confirmed'])
                                            ->count();
        $newMessagesCount = ContactMessage::where('status', 'NEW')->count();

        // Restaurant Status
        $isOpen = $hoursService->isOpenNow();

        // Needs Attention
        $needsAttention = [
            'orders' => TakeawayOrder::where('status', 'RECEIVED')->get(),
            'reservations' => Reservation::where('status', 'PENDING')->get(),
            'venue_bookings' => VenueBooking::where('status', 'requested')->get(),
            'messages' => ContactMessage::where('status', 'NEW')->get(),
        ];

        $attentionCount = count($needsAttention['orders']) + count($needsAttention['reservations']) + count($needsAttention['venue_bookings']) + count($needsAttention['messages']);

        // Today's Timeline
        $timeline = collect();

        // Add reservations to timeline
        Reservation::whereDate('reservation_date', $today)
            ->whereIn('status', ['PENDING', 'CONFIRMED'])
            ->get()
            ->each(function ($res) use ($timeline) {
                $timeline->push([
                    'time' => Carbon::parse($res->reservation_time)->format('H:i'),
                    'type' => 'Reservation',
                    'title' => "Reservation — {$res->guests} guests",
                    'subtitle' => $res->reservation_reference,
                    'link' => route('admin.bookings.show', $res->reservation_reference),
                    'timestamp' => Carbon::parse($res->reservation_time)->timestamp
                ]);
            });

        // Add venue bookings to timeline
        VenueBooking::whereDate('event_date', $today)
            ->whereIn('status', ['approved', 'confirmed'])
            ->get()
            ->each(function ($vb) use ($timeline) {
                $timeline->push([
                    'time' => Carbon::parse($vb->start_time)->format('H:i'),
                    'type' => 'Venue',
                    'title' => "Venue — {$vb->event_title}",
                    'subtitle' => "Guests: {$vb->guest_count}",
                    'link' => route('admin.bookings.show', $vb->reference),
                    'timestamp' => Carbon::parse($vb->start_time)->timestamp
                ]);
            });

        // Add takeaway orders to timeline
        TakeawayOrder::whereDate('pickup_time', $today)
            ->whereNotIn('status', ['CANCELLED', 'COMPLETED'])
            ->get()
            ->each(function ($order) use ($timeline) {
                $timeline->push([
                    'time' => $order->pickup_time->format('H:i'),
                    'type' => 'Takeaway',
                    'title' => "Takeaway pickup",
                    'subtitle' => $order->reference,
                    'link' => route('admin.orders.show', $order->reference),
                    'timestamp' => $order->pickup_time->timestamp
                ]);
            });

        $sortedTimeline = $timeline->sortBy('timestamp')->values();

        return view('livewire.admin.dashboard', [
            'adminName' => auth('admin')->user()->name,
            'kpis' => [
                'takeaway' => $takeawayOrdersCount,
                'reservations' => $reservationsCount,
                'venues' => $venueBookingsCount,
                'messages' => $newMessagesCount,
            ],
            'isOpen' => $isOpen,
            'needsAttention' => $needsAttention,
            'attentionCount' => $attentionCount,
            'timeline' => $sortedTimeline,
        ])->title('Operational Dashboard - Vanniyan Admin');
    }
}
