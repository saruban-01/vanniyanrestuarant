<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeawayOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TakeawayOrderItem::class);
    }
}
