<?php

namespace App\Livewire\Admin\Menu;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MenuItem;
use App\Models\MenuCategory;

#[Layout('components.layouts.admin')]
class ItemEditor extends Component
{
    public ?MenuItem $item = null;
    
    public $name = '';
    public $description = '';
    public $price = '';
    public $menu_category_id = '';
    public $is_active = true;
    public $is_signature = false;
    public $sort_order = 0;
    public $image_url = '';

    public function mount(?MenuItem $item = null)
    {
        if ($item && $item->exists) {
            $this->item = $item;
            $this->name = $item->name;
            $this->description = $item->description;
            $this->price = $item->price;
            $this->menu_category_id = $item->menu_category_id;
            $this->is_active = $item->is_active;
            $this->is_signature = $item->is_signature;
            $this->sort_order = $item->sort_order;
            $this->image_url = $item->image_url;
        } else {
            $this->sort_order = MenuItem::max('sort_order') + 10;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'is_active' => 'boolean',
            'is_signature' => 'boolean',
            'sort_order' => 'required|integer',
            'image_url' => 'nullable|string',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'menu_category_id' => $this->menu_category_id,
            'is_active' => $this->is_active,
            'is_signature' => $this->is_signature,
            'sort_order' => $this->sort_order,
            'image_url' => $this->image_url,
        ];

        if ($this->item) {
            $this->item->update($data);
            session()->flash('message', 'Menu item updated successfully.');
        } else {
            MenuItem::create($data);
            session()->flash('message', 'Menu item created successfully.');
        }

        return redirect()->route('admin.menu');
    }

    public function render()
    {
        return view('livewire.admin.menu.item-editor', [
            'categories' => MenuCategory::orderBy('sort_order')->get(),
        ])->title($this->item ? 'Edit Menu Item - Vanniyan CMS' : 'New Menu Item - Vanniyan CMS');
    }
}