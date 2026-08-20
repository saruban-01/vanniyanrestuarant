<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Reservation;
use App\Models\VenueBooking;
use Illuminate\Support\Collection;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $filter = 'all'; // all, table, venue, requested, contacted, confirmed, declined, cancelled, completed, no-show
    
    // Polling interval for real-time updates
    public function render()
    {
        $tableBookings = collect();
        $venueBookings = collect();

        // Fetch Table Reservations
        if (in_array($this->filter, ['all', 'table', 'requested', 'confirmed', 'cancelled', 'completed', 'no-show'])) {
            $query = Reservation::with('table')->orderBy('created_at', 'desc');
            
            if ($this->filter === 'requested') {
                $query->where('status', 'pending');
            } elseif ($this->filter === 'confirmed') {
                $query->where('status', 'confirmed');
            } elseif ($this->filter === 'cancelled') {
                $query->where('status', 'cancelled');
            } elseif ($this->filter === 'completed') {
                $query->where('status', 'completed');
            } elseif ($this->filter === 'no-show') {
                $query->where('status', 'no_show');
            }
            
            $tableBookings = $query->get()->map(function ($booking) {
                return (object) [
                    'type' => 'table',
                    'id' => $booking->id,
                    'reference' => $booking->reservation_reference,
                    'customer_name' => $booking->customer_name,
                    'phone' => $booking->phone,
                    'date' => $booking->reservation_date,
                    'time' => $booking->reservation_time,
                    'guests' => $booking->guests,
                    'status' => $booking->status === 'pending' ? 'requested' : str_replace('_', '-', $booking->status),
                    'created_at' => $booking->created_at,
                    'model' => $booking
                ];
            });
        }

        // Fetch Venue Bookings
        if (in_array($this->filter, ['all', 'venue', 'requested', 'contacted', 'confirmed', 'declined', 'cancelled', 'completed'])) {
            $query = VenueBooking::with(['venue', 'eventType'])->orderBy('created_at', 'desc');
            
            if ($this->filter === 'requested') {
                $query->where('status', 'requested');
            } elseif ($this->filter === 'contacted') {
                $query->where('status', 'contacted');
            } elseif ($this->filter === 'confirmed') {
                $query->where('status', 'confirmed'); // assuming 'confirmed' replaces 'approved'
            } elseif ($this->filter === 'declined') {
                $query->where('status', 'declined');
            } elseif ($this->filter === 'cancelled') {
                $query->where('status', 'cancelled');
            } elseif ($this->filter === 'completed') {
                $query->where('status', 'completed');
            }
            
            $venueBookings = $query->get()->map(function ($booking) {
                return (object) [
                    'type' => 'venue',
                    'id' => $booking->id,
                    'reference' => $booking->reference,
                    'customer_name' => $booking->customer_name,
                    'phone' => $booking->phone,
                    'date' => $booking->event_date,
                    'time' => $booking->start_time,
                    'guests' => $booking->guest_count,
                    'status' => $booking->status,
                    'created_at' => $booking->created_at,
                    'model' => $booking
                ];
            });
        }

        $allBookings = $tableBookings->concat($venueBookings)
            ->sortByDesc('created_at');

        // Simple manual pagination for the collection
        $page = $this->getPage();
        $perPage = 15;
        
        $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $allBookings->forPage($page, $perPage),
            $allBookings->count(),
            $perPage,
            $page,
            ['path' => route('admin.bookings.index')]
        );

        return view('livewire.admin.bookings.index', [
            'bookings' => $paginated
        ])->layout('components.layouts.admin');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }
}
