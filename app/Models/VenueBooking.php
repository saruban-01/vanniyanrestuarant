<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueBooking extends Model
{
    protected $fillable = [
        'reference',
        'secure_token',
        'venue_id',
        'event_type_id',
        'event_title',
        'event_date',
        'start_time',
        'end_time',
        'guest_count',
        'customer_name',
        'phone',
        'email',
        'special_request',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function eventType()
    {
        return $this->belongsTo(VenueEventType::class, 'event_type_id');
    }

    public function services()
    {
        return $this->belongsToMany(VenueService::class, 'venue_booking_services')
                    ->withPivot(['snapshot_name', 'snapshot_price_type', 'snapshot_base_price', 'quoted_price', 'is_included'])
                    ->withTimestamps();
    }

    public function quotes()
    {
        return $this->hasMany(VenueQuoteVersion::class);
    }

    public function latestQuote()
    {
        return $this->hasOne(VenueQuoteVersion::class)->latestOfMany('version_number');
    }
}
