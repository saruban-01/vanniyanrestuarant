<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = [
        'name',
        'description',
        'max_capacity',
        'location',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'max_capacity' => 'integer',
        'sort_order' => 'integer',
    ];

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class);
    }
}
