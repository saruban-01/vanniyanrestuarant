<?php

namespace App\Livewire\Admin\Tables;

use App\Models\RestaurantTable;
use App\Models\Reservation;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public $tables;
    public $showModal = false;

    // Form fields
    public $tableId;
    public $tableNumber;
    public $capacity;
    public $location;
    public $isActive = true;

    protected $rules = [
        'tableNumber' => 'required|string|max:50',
        'capacity' => 'required|integer|min:1',
        'location' => 'nullable|string|max:100',
        'isActive' => 'boolean',
    ];

    public function mount()
    {
        $this->loadTables();
    }

    public function loadTables()
    {
        $this->tables = RestaurantTable::orderBy('table_number')->get();
    }

    public function createTable()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function editTable($id)
    {
        $this->resetForm();
        $table = RestaurantTable::findOrFail($id);
        
        $this->tableId = $table->id;
        $this->tableNumber = $table->table_number;
        $this->capacity = $table->capacity;
        $this->location = $table->location;
        $this->isActive = $table->is_active;

        $this->showModal = true;
    }

    public function saveTable()
    {
        $this->validate();

        // Check unique table number
        $query = RestaurantTable::where('table_number', $this->tableNumber);
        if ($this->tableId) {
            $query->where('id', '!=', $this->tableId);
        }
        if ($query->exists()) {
            $this->addError('tableNumber', 'Table number already exists.');
            return;
        }

        if ($this->tableId) {
            $table = RestaurantTable::findOrFail($this->tableId);
            
            // Check if attempting to deactivate
            if ($table->is_active && !$this->isActive) {
                // Verify if upcoming reservations exist
                $upcoming = Reservation::where('table_id', $this->tableId)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->whereDate('reservation_date', '>=', today())
                    ->exists();

                if ($upcoming) {
                    session()->flash('error', 'Cannot deactivate table. There are upcoming reservations assigned to it. Please reassign them first.');
                    return;
                }
            }

            $table->update([
                'table_number' => $this->tableNumber,
                'capacity' => $this->capacity,
                'location' => $this->location,
                'is_active' => $this->isActive,
            ]);

            AuditLog::log(
                Auth::guard('admin')->user(),
                'restaurant_table_updated',
                "Table {$this->tableNumber} updated",
                ['table_id' => $table->id]
            );

            session()->flash('success', 'Table updated successfully.');
        } else {
            $table = RestaurantTable::create([
                'table_number' => $this->tableNumber,
                'capacity' => $this->capacity,
                'location' => $this->location,
                'is_active' => $this->isActive,
            ]);

            AuditLog::log(
                Auth::guard('admin')->user(),
                'restaurant_table_created',
                "Table {$this->tableNumber} created",
                ['table_id' => $table->id]
            );

            session()->flash('success', 'Table created successfully.');
        }

        $this->showModal = false;
        $this->loadTables();
    }

    public function resetForm()
    {
        $this->reset(['tableId', 'tableNumber', 'capacity', 'location']);
        $this->isActive = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.tables.index')->title('Table Management - Vanniyan Admin');
    }
}
