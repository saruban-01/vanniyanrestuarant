<?php

namespace App\Livewire\Admin\Menu;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MenuCategory;
use App\Models\MenuItem;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public function toggleItemStatus($itemId)
    {
        $item = MenuItem::findOrFail($itemId);
        $item->is_active = !$item->is_active;
        $item->save();
        
        session()->flash('message', 'Item status updated.');
    }

    public function deleteItem($itemId)
    {
        MenuItem::findOrFail($itemId)->delete();
        session()->flash('message', 'Item deleted.');
    }

    public function deleteCategory($categoryId)
    {
        $category = MenuCategory::findOrFail($categoryId);

        if ($category->items()->count() > 0) {
            session()->flash('error', 'Cannot delete "'. $category->name .'" — it still contains menu items. Move or delete them first.');
            return;
        }

        $category->delete();
        session()->flash('message', 'Category deleted.');
    }

    public function render()
    {
        return view('livewire.admin.menu.index', [
            'categories' => MenuCategory::with('items')->orderBy('sort_order')->get()
        ])->title('Menu Management - Vanniyan CMS');
    }
}
