<?php

namespace App\Services;

use App\Models\MenuItem;

class PricingService
{
    /**
     * Calculate the line total for an item.
     */
    public function calculateLineTotal(MenuItem $item, int $quantity): float
    {
        return $item->price * $quantity;
    }
}