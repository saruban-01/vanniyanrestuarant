<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\TakeawayOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TakeawayOrderService
{
    protected $pricingService;
    protected $validationService;

    public function __construct(PricingService $pricingService, TakeawayValidationService $validationService)
    {
        $this->pricingService = $pricingService;
        $this->validationService = $validationService;
    }

    public function createOrder(array $cartItems, array $customerDetails, $pickupTime)
    {
        // 1. Validate
        $this->validationService->validateCart($cartItems);
        $this->validationService->validatePickupTime($pickupTime);

        // 2. Transaction
        return DB::transaction(function () use ($cartItems, $customerDetails, $pickupTime) {
            
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($cartItems as $cartItem) {
                $menuItem = MenuItem::find($cartItem['menu_item_id']);
                $quantity = $cartItem['quantity'];

                $lineTotal = $this->pricingService->calculateLineTotal($menuItem, $quantity);
                $subtotal += $lineTotal;

                $orderItemsData[] = [
                    'menu_item_id' => $menuItem->id,
                    'item_name_snapshot' => $menuItem->name,
                    'unit_price_snapshot' => $menuItem->price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            }

            // Create Order
            $order = TakeawayOrder::create([
                'reference' => 'VAN-TA-' . strtoupper(Str::random(5)) . date('y'),
                'access_token' => Str::random(40),
                'status' => 'received',
                'customer_name' => $customerDetails['name'],
                'customer_phone' => $customerDetails['phone'],
                'customer_email' => $customerDetails['email'] ?? null,
                'order_note' => $customerDetails['note'] ?? null,
                'pickup_time' => $pickupTime,
                'subtotal' => $subtotal,
                'total' => $subtotal, // Assuming no tax or fees are added at this stage
            ]);

            // Create Order Items
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Admin Notification
            \App\Models\AdminNotification::notify(
                'NEW_TAKEAWAY_ORDER',
                'New Takeaway Order',
                "Order {$order->reference} from {$order->customer_name} — Rs. " . number_format($order->total, 2) . " for pickup at " . $order->pickup_time->format('H:i'),
                'takeaway_order',
                $order->id
            );

            return $order;
        });
    }
}