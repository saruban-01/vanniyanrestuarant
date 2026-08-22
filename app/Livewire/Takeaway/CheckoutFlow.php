<?php

namespace App\Livewire\Takeaway;

use App\Models\MenuItem;
use App\Services\PickupSlotService;
use App\Services\PricingService;
use App\Services\RestaurantHoursService;
use App\Services\TakeawayOrderService;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class CheckoutFlow extends Component
{
    public $isOpen = false;
    public $step = 1; // 1: Review, 2: Pickup, 3: Details, 4: Final Review
    
    public $cart = [];
    public $displayCart = [];
    public $subtotal = 0;

    // Pickup
    public $pickupSlots = [];
    public $selectedPickupTime = null;

    // Details
    public $customerName = '';
    public $customerPhone = '';
    public $customerEmail = '';
    public $orderNote = '';

    public $isProcessing = false;

    #[On('open-checkout-flow')]
    public function open()
    {
        if (!app(RestaurantHoursService::class)->isOpenNow()) {
            session()->flash('error', 'We\'re currently closed. Takeaway ordering is available during our opening hours.');
            return;
        }

        $this->cart = Session::get('takeaway_cart', []);
        
        if (empty($this->cart)) {
            return;
        }

        $this->buildDisplayCart();
        $this->loadPickupSlots();
        
        $this->step = 1;
        $this->isOpen = true;

        $this->dispatch('vanniyan-track', [
            'event' => 'begin_checkout',
            'data' => [
                'item_count' => array_sum(array_column($this->cart, 'quantity')),
                'value' => (float) $this->subtotal,
                'items' => array_map(fn ($line) => $line['menu_item_id'], $this->cart),
            ],
        ]);
    }

    public function close()
    {
        $this->isOpen = false;
        $this->step = 1;
    }

    public function nextStep()
    {
        if ($this->step === 2) {
            $this->validate([
                'selectedPickupTime' => 'required',
            ], [
                'selectedPickupTime.required' => 'Please select a pickup time.'
            ]);
        }
        
        if ($this->step === 3) {
            $this->validate([
                'customerName' => 'required|min:2|max:255',
                'customerPhone' => 'required|min:10|max:15',
                'customerEmail' => 'nullable|email|max:255',
                'orderNote' => 'nullable|max:500',
            ]);
        }

        if ($this->step < 4) {
            $this->step++;
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function placeOrder(TakeawayOrderService $orderService)
    {
        // Re-check the restaurant is open
        if (!app(RestaurantHoursService::class)->isOpenNow()) {
            session()->flash('error', 'We\'re currently closed. Takeaway ordering is available during our opening hours.');
            return;
        }

        // Re-validate details to be safe
        $this->validate([
            'customerName' => 'required|min:2|max:255',
            'customerPhone' => 'required|min:10|max:15',
            'selectedPickupTime' => 'required',
        ]);

        if ($this->isProcessing) return;
        $this->isProcessing = true;

        try {
            $order = $orderService->createOrder(
                $this->cart,
                [
                    'name' => $this->customerName,
                    'phone' => $this->customerPhone,
                    'email' => $this->customerEmail,
                    'note' => $this->orderNote,
                ],
                $this->selectedPickupTime
            );

            // Clear Cart
            Session::forget('takeaway_cart');
            $this->cart = [];
            $this->displayCart = [];

            // Redirect to confirmation (include the access token so the
            // unauthenticated confirmation page can verify ownership of the link)
            return redirect()->route('takeaway.confirmation', [
                'reference' => $order->reference,
                'token' => $order->access_token,
            ]);
            
        } catch (\Exception $e) {
            $this->isProcessing = false;
            // In a real app, parse ValidationException nicely
            session()->flash('error', $e->getMessage());
        }
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
                'menu_item' => $menuItem,
                'quantity' => $cartItem['quantity'],
                'line_total' => $lineTotal,
            ];
        }
    }

    protected function loadPickupSlots()
    {
        $pickupService = app(PickupSlotService::class);
        $this->pickupSlots = $pickupService->getAvailableSlots();
        if (count($this->pickupSlots) > 0 && !$this->selectedPickupTime) {
            $this->selectedPickupTime = $this->pickupSlots[0]['value'];
        }
    }

    public function render()
    {
        return view('livewire.takeaway.checkout-flow');
    }
}
