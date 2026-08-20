<?php

namespace App\Livewire\Admin\Bookings;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\VenueBooking;

class Show extends Component
{
    public $reference;
    public $type;
    public $booking;
    
    public $adminNotes;

    public function mount($reference)
    {
        $this->reference = $reference;
        
        // Try finding in Reservation
        $this->booking = Reservation::with('table')->where('reservation_reference', $reference)->first();
        if ($this->booking) {
            $this->type = 'table';
            $this->adminNotes = $this->booking->admin_notes ?? '';
            return;
        }

        // Try finding in VenueBooking
        $this->booking = VenueBooking::with(['venue', 'eventType', 'services'])->where('reference', $reference)->first();
        if ($this->booking) {
            $this->type = 'venue';
            $this->adminNotes = $this->booking->admin_notes ?? '';
            return;
        }

        abort(404, 'Booking not found');
    }

    public function updateStatus($status)
    {
        if ($this->type === 'table') {
            // Map statuses
            $tableStatus = $status;
            if ($status === 'requested') $tableStatus = 'pending';
            if ($status === 'contacted') $tableStatus = 'pending'; // Table reservations don't really have contacted state natively, keep pending or map if added later
            
            $this->booking->update(['status' => $tableStatus]);
        } else {
            $this->booking->update(['status' => $status]);
        }
        
        $this->booking->refresh();
        session()->flash('success', 'Status updated successfully.');
    }

    public function saveNotes()
    {
        $this->booking->update(['admin_notes' => $this->adminNotes]);
        session()->flash('success', 'Notes saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.bookings.show')->layout('components.layouts.admin');
    }
}
