<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeawayOrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(TakeawayOrder::class, 'takeaway_order_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
