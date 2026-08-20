<?php

namespace App\Livewire\Takeaway;

use App\Models\MenuItem;
use App\Services\PricingService;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class CartSidebar extends Component
{
    public $cart = [];
    public $subtotal = 0;
    
    // We store rich data temporarily for display, but session just holds IDs/Qty
    public $displayCart = [];

    public function mount()
    {
        $this->cart = Session::get('takeaway_cart', []);
        $this->buildDisplayCart();

        if (! empty($this->cart)) {
            $this->dispatch('vanniyan-track', [
                'event' => 'view_cart',
                'data' => [
                    'item_count' => array_sum(array_column($this->cart, 'quantity')),
                    'value' => (float) $this->subtotal,
                ],
            ]);
        }
    }

    #[On('cart-item-added')]
    public function addToCart($item)
    {
        $menuItem = MenuItem::find($item['menu_item_id'] ?? null);
        $menuItemId = (int) ($item['menu_item_id'] ?? 0);
        $quantity = max(1, (int) ($item['quantity'] ?? 1));

        $found = false;
        foreach ($this->cart as $index => $line) {
            if ((int) $line['menu_item_id'] === $menuItemId) {
                $this->cart[$index]['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (! $found) {
            $this->cart[] = [
                'id' => uniqid(),
                'menu_item_id' => $menuItemId,
                'quantity' => $quantity,
            ];
        }

        $this->saveCart();

        $this->dispatch('notify', type: 'success', message: 'Added to your order: '.($menuItem?->name ?? 'Item'));

        if ($menuItem) {
            $this->dispatch('vanniyan-track', [
                'event' => 'add_to_cart',
                'data' => [
                    'item_id' => $menuItem->id,
                    'item_name' => $menuItem->name,
                    'category' => $menuItem->category?->name,
                    'quantity' => $quantity,
                    'price' => (float) $menuItem->price,
                ],
                'consent' => 'marketing',
            ]);
        }
    }

    public function removeItem($cartId)
    {
        $removed = null;
        foreach ($this->cart as $item) {
            if ($item['id'] === $cartId) {
                $removed = $item;
                break;
            }
        }

        $this->cart = array_filter($this->cart, function ($item) use ($cartId) {
            return $item['id'] !== $cartId;
        });
        
        // Re-index array
        $this->cart = array_values($this->cart);
        $this->saveCart();

        if ($removed) {
            $menuItem = \App\Models\MenuItem::find($removed['menu_item_id'] ?? null);
            $this->dispatch('vanniyan-track', [
                'event' => 'remove_from_cart',
                'data' => [
                    'item_id' => $removed['menu_item_id'] ?? null,
                    'item_name' => $menuItem?->name,
                    'quantity' => (int) ($removed['quantity'] ?? 0),
                    'price' => $menuItem ? (float) $menuItem->price : 0,
                ],
                'consent' => 'marketing',
            ]);
        }
    }

    #[On('cart-decrement')]
    public function decrementItem($menuItemId)
    {
        foreach ($this->cart as $index => $line) {
            if ((int) $line['menu_item_id'] === (int) $menuItemId) {
                $this->cart[$index]['quantity'] -= 1;

if ($this->cart[$index]['quantity'] <= 0) {
                unset($this->cart[$index]);
            }

            $this->cart = array_values($this->cart);
            $this->saveCart();

            $menuItem = \App\Models\MenuItem::find($menuItemId);
            $this->dispatch('vanniyan-track', [
                'event' => 'remove_from_cart',
                'data' => [
                    'item_id' => $menuItemId,
                    'item_name' => $menuItem?->name,
                    'quantity' => 1,
                    'price' => $menuItem ? (float) $menuItem->price : 0,
                ],
                'consent' => 'marketing',
            ]);

            return;
            }
        }
    }

    public function increment($menuItemId)
    {
        $menuItem = MenuItem::find($menuItemId);

        foreach ($this->cart as $index => $line) {
            if ((int) $line['menu_item_id'] === (int) $menuItemId) {
                $this->cart[$index]['quantity'] += 1;
                $this->saveCart();
                $this->dispatch('notify', type: 'success', message: 'Added to your order: '.($menuItem?->name ?? 'Item'));
                $this->dispatch('vanniyan-track', [
                    'event' => 'add_to_cart',
                    'data' => [
                        'item_id' => $menuItemId,
                        'item_name' => $menuItem?->name,
                        'category' => $menuItem?->category?->name,
                        'quantity' => 1,
                        'price' => $menuItem ? (float) $menuItem->price : 0,
                    ],
                    'consent' => 'marketing',
                ]);
                return;
            }
        }
    }

    protected function saveCart()
    {
        Session::put('takeaway_cart', $this->cart);
        $this->buildDisplayCart();
        $this->dispatch('cart-updated');
    }

    protected function buildDisplayCart()
    {
        $this->displayCart = [];
        $this->subtotal = 0;
        $pricingService = app(PricingService::class);

        foreach ($this->cart as $cartItem) {
            $menuItem = MenuItem::find($cartItem['menu_item_id']);
            if (!$menuItem) continue;

            $lineTotal = $pricingService->calculateLineTotal($menuItem, $cartItem['quantity']);
            $this->subtotal += $lineTotal;

            $this->displayCart[] = [
                'id' => $cartItem['id'],
                'menu_item' => $menuItem,
                'quantity' => $cartItem['quantity'],
                'line_total' => $lineTotal,
            ];
        }
    }

    public function checkout()
    {
        $this->dispatch('open-checkout-flow');
    }

    public function render()
    {
        return view('livewire.takeaway.cart-sidebar');
    }
}