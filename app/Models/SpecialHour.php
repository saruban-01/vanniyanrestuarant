<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'open_time',
        'close_time',
        'is_closed',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];
}
