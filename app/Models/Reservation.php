<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_reference',
        'customer_name',
        'phone',
        'email',
        'reservation_date',
        'reservation_time',
        'duration_minutes',
        'guests',
        'table_id',
        'special_request',
        'status',
        'idempotency_key',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'duration_minutes' => 'integer',
        'guests' => 'integer',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class, 'table_id');
    }
}
