<?php

namespace App\Livewire\Admin\Seo;

use App\Models\Redirect;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Redirects extends Component
{
    use WithPagination;

    public $search = '';
    
    // Form fields
    public $editingId = null;
    public $old_path = '';
    public $new_path = '';
    public $status_code = 301;
    public $is_active = true;

    protected $rules = [
        'old_path' => 'required|string|max:255',
        'new_path' => 'required|string|max:255',
        'status_code' => 'required|in:301,302',
        'is_active' => 'boolean',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetFields();
        $this->editingId = 'new';
    }

    public function edit($id)
    {
        $redirect = Redirect::findOrFail($id);
        $this->editingId = $redirect->id;
        $this->old_path = $redirect->old_path;
        $this->new_path = $redirect->new_path;
        $this->status_code = $redirect->status_code;
        $this->is_active = $redirect->is_active;
    }

    public function cancel()
    {
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->editingId = null;
        $this->old_path = '';
        $this->new_path = '';
        $this->status_code = 301;
        $this->is_active = true;
    }

    public function save()
    {
        // Strip leading slash if present, for consistency
        $this->old_path = ltrim($this->old_path, '/');
        
        $this->validate([
            'old_path' => 'required|string|max:255|unique:redirects,old_path,' . ($this->editingId === 'new' ? 'NULL' : $this->editingId),
            'new_path' => 'required|string|max:255',
            'status_code' => 'required|in:301,302',
            'is_active' => 'boolean',
        ]);

        if ($this->old_path === ltrim($this->new_path, '/')) {
            $this->addError('new_path', 'New path cannot be the same as old path (infinite loop).');
            return;
        }

        if ($this->editingId === 'new') {
            Redirect::create([
                'old_path' => $this->old_path,
                'new_path' => $this->new_path,
                'status_code' => $this->status_code,
                'is_active' => $this->is_active,
            ]);
            $action = 'created';
        } else {
            $redirect = Redirect::findOrFail($this->editingId);
            $redirect->update([
                'old_path' => $this->old_path,
                'new_path' => $this->new_path,
                'status_code' => $this->status_code,
                'is_active' => $this->is_active,
            ]);
            $action = 'updated';
        }

        \App\Models\AuditLog::log(
            \Illuminate\Support\Facades\Auth::guard('admin')->user(),
            "redirect_{$action}",
            "Redirect {$this->old_path} -> {$this->new_path} {$action}."
        );

        session()->flash('success', "Redirect {$action} successfully.");
        $this->resetFields();
    }

    public function delete($id)
    {
        $redirect = Redirect::findOrFail($id);
        $path = $redirect->old_path;
        $redirect->delete();
        
        \App\Models\AuditLog::log(
            \Illuminate\Support\Facades\Auth::guard('admin')->user(),
            "redirect_deleted",
            "Redirect {$path} deleted."
        );

        session()->flash('success', 'Redirect deleted successfully.');
    }

    public function toggleActive($id)
    {
        $redirect = Redirect::findOrFail($id);
        $redirect->update(['is_active' => !$redirect->is_active]);
    }

    public function render()
    {
        $redirects = Redirect::when($this->search, function ($query) {
            $query->where('old_path', 'like', '%' . $this->search . '%')
                  ->orWhere('new_path', 'like', '%' . $this->search . '%');
        })->latest()->paginate(20);

        return view('livewire.admin.seo.redirects', [
            'redirects' => $redirects
        ])->title('Redirects - Vanniyan Admin');
    }
}
