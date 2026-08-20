<?php

namespace App\Livewire\Menu;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\RestaurantHoursService;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class CategoryModal extends Component
{
    public $isOpen = false;
    public $category = null;
    public $items = [];
    public $mode = 'dinein';
    public $restaurantOpen = false;
    public $cartCounts = [];

    public function mount()
    {
        $this->refreshCartCounts();
    }

    #[On('cart-updated')]
    public function refreshCartCounts()
    {
        $counts = [];
        foreach (Session::get('takeaway_cart', []) as $line) {
            $id = $line['menu_item_id'];
            $counts[$id] = ($counts[$id] ?? 0) + (int) $line['quantity'];
        }
        $this->cartCounts = $counts;
    }

    public function quickAdd($itemId)
    {
        $this->dispatch('cart-item-added', [
            'menu_item_id' => $itemId,
            'quantity' => 1,
        ]);
        $this->refreshCartCounts();
    }

    public function decrement($itemId)
    {
        $this->dispatch('cart-decrement', menuItemId: $itemId);
        $this->refreshCartCounts();
    }

    #[On('open-category')]
    public function open($categoryId, $mode = 'dinein', $restaurantOpen = false)
    {
        $this->category = MenuCategory::find($categoryId);
        $this->mode = $mode;
        $this->restaurantOpen = $restaurantOpen;

        if (!$this->category) {
            return;
        }

        $this->items = $this->category->items()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->category = null;
        $this->items = [];
    }

    public function render()
    {
        return view('livewire.menu.category-modal');
    }
}