<?php

namespace App\Livewire\Pages;

use App\Models\TakeawayOrder;
use App\Services\OrderStatusService;
use App\Services\RestaurantSettingsService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class OrderStatusPage extends Component
{
    public $reference;
    public $token;
    public $order;
    
    public $restaurantName;
    public $restaurantAddress;
    public $restaurantPhone;
    public $restaurantMapsUrl;

    public function mount($reference, OrderStatusService $statusService, RestaurantSettingsService $settingsService)
    {
        $this->reference = $reference;
        $this->token = request()->query('token');

        $this->loadOrder($statusService);

        $settings = $settingsService->getAll();
        $this->restaurantName = 'Vanniyan Restaurant';
        $this->restaurantAddress = $settings['address'] ?? '';
        $this->restaurantPhone = $settings['phone'] ?? '';
        $this->restaurantMapsUrl = $settings['maps_url'] ?? '#';
    }

    public function refreshStatus(OrderStatusService $statusService)
    {
        if (!$this->order) return;

        // Stop polling if the order is completed or cancelled
        if (in_array($this->order->status, [OrderStatusService::STATUS_COMPLETED, OrderStatusService::STATUS_CANCELLED])) {
            return;
        }

        $this->loadOrder($statusService);
    }

    private function loadOrder(OrderStatusService $statusService)
    {
        if (!$this->token) {
            $this->order = null;
            return;
        }

        $this->order = $statusService->validateAccess($this->reference, $this->token);
        
        if ($this->order) {
            $this->order->load('items');
        }
    }

    public function render(OrderStatusService $statusService)
    {
        return view('livewire.pages.order-status-page', [
            'statusService' => $statusService,
        ])->title('Order Status - Vanniyan Restaurant');
    }
}
