<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class BookingSelection extends Component
{
    public function render()
    {
        return view('livewire.pages.booking-selection')->layout('components.layouts.app', [
            'title' => 'Book a Table or Venue at Vanniyan Restaurant | Kilinochchi',
            'meta_description' => 'Book a table for dining at Vanniyan or request our available outdoor venue for your own celebration, gathering or function in Kilinochchi.',
        ]);
    }
}
