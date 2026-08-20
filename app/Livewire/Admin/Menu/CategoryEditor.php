<?php

namespace App\Livewire\Admin\Menu;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\MenuCategory;

#[Layout('components.layouts.admin')]
class CategoryEditor extends Component
{
    public ?MenuCategory $category = null;
    
    public $name = '';
    public $description = '';
    public $sort_order = 0;

    public function mount(?MenuCategory $category = null)
    {
        if ($category && $category->exists) {
            $this->category = $category;
            $this->name = $category->name;
            $this->description = $category->description;
            $this->sort_order = $category->sort_order;
        } else {
            $this->sort_order = MenuCategory::max('sort_order') + 10;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        if ($this->category) {
            $this->category->update([
                'name' => $this->name,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
            ]);
            session()->flash('message', 'Category updated successfully.');
        } else {
            MenuCategory::create([
                'name' => $this->name,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'slug' => \Str::slug($this->name), // Not strictly required but good to have
            ]);
            session()->flash('message', 'Category created successfully.');
        }

        return redirect()->route('admin.menu');
    }

    public function render()
    {
        return view('livewire.admin.menu.category-editor')
            ->title($this->category ? 'Edit Category - Vanniyan CMS' : 'New Category - Vanniyan CMS');
    }
}
