<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueBlackout extends Model
{
    protected $fillable = [
        'venue_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
