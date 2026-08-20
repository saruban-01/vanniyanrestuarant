<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueQuoteVersion extends Model
{
    protected $fillable = [
        'venue_booking_id',
        'version_number',
        'venue_fee',
        'services_fee',
        'tax_amount',
        'quoted_total',
        'currency',
        'admin_user_id',
    ];

    protected $casts = [
        'version_number' => 'integer',
        'venue_fee' => 'decimal:2',
        'services_fee' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'quoted_total' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(VenueBooking::class, 'venue_booking_id');
    }

    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}
