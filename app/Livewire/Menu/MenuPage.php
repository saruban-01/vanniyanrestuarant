<?php

namespace App\Livewire\Menu;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Services\RestaurantHoursService;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.app')]
class MenuPage extends Component
{
    #[Url]
    public $mode = 'dinein'; // dinein or takeaway

    #[Url]
    public $search = '';

    #[Url]
    public $category = '';

    public $cartCounts = [];

    public function mount()
    {
        $this->refreshCartCounts();

        $this->dispatch('vanniyan-track', [
            'event' => 'view_menu',
            'data' => [
                'mode' => $this->mode,
            ],
        ]);
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
        if (! app(RestaurantHoursService::class)->isOpenNow()) {
            $this->dispatch('notify', type: 'error', message: 'We\'re currently closed. Takeaway ordering is available during our opening hours.');
            return;
        }

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

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function setCategory($slug)
    {
        $this->category = $slug;
    }

    public function openCategory($categoryId)
    {
        $category = \App\Models\MenuCategory::with('items')->find($categoryId);

        if ($category) {
            $this->dispatch('vanniyan-track', [
                'event' => 'view_item',
                'data' => [
                    'category_id' => $category->id,
                    'category_name' => $category->name,
                    'mode' => $this->mode,
                    'items' => $category->items
                        ->where('is_active', true)
                        ->map(fn ($item) => [
                            'item_id' => $item->id,
                            'item_name' => $item->name,
                            'price' => (float) $item->price,
                        ])
                        ->values()
                        ->all(),
                ],
            ]);
        }

        $this->dispatch('open-category', categoryId: $categoryId, mode: $this->mode, restaurantOpen: app(RestaurantHoursService::class)->isOpenNow());
    }

    public function render()
    {
        $hoursService = app(RestaurantHoursService::class);
        $categories = MenuCategory::with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($c) => $c->items->isNotEmpty());
        
        $query = MenuItem::with('category')->where('is_active', true);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        } elseif ($this->category) {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->category);
            });
        }

        $items = $query->orderBy('sort_order')->get();

        return view('livewire.menu.menu-page', [
            'categories' => $categories,
            'items' => $items,
            'isOpen' => $hoursService->isOpenNow(),
            'nextOpening' => $hoursService->getNextOpeningTime(),
        ]);
    }
}
