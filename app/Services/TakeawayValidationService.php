<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Validation\ValidationException;

class TakeawayValidationService
{
    public function validateCart(array $cartItems)
    {
        if (empty($cartItems)) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        foreach ($cartItems as $index => $cartItem) {
            $menuItem = MenuItem::find($cartItem['menu_item_id']);

            if (!$menuItem || !$menuItem->is_active) {
                throw ValidationException::withMessages(["cart" => "An item in your cart is no longer available."]);
            }

            if ($cartItem['quantity'] < 1 || $cartItem['quantity'] > 20) {
                throw ValidationException::withMessages(["cart" => "Invalid quantity for {$menuItem->name}."]);
            }
        }

        return true;
    }

    public function validatePickupTime($pickupTime)
    {
        if (!$pickupTime) {
            throw ValidationException::withMessages(['pickup' => 'Please select a pickup time.']);
        }

        // In a real app, validate that $pickupTime is within open hours and is a valid slot
        return true;
    }
}