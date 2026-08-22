<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
