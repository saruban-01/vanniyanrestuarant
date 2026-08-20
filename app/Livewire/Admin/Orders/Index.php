<?php

namespace App\Livewire\Admin\Orders;

use App\Models\TakeawayOrder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Carbon\Carbon;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'ALL';
    public $dateFilter = 'TODAY'; // TODAY, UPCOMING, ACTIVE, COMPLETED, CANCELLED, ALL

    protected $queryString = ['search', 'status', 'dateFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function setDateFilter($filter)
    {
        $this->dateFilter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        $query = TakeawayOrder::with('items')->orderBy('pickup_time', 'desc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status !== 'ALL') {
            $query->where('status', strtolower($this->status));
        }

        $today = Carbon::today();

        switch ($this->dateFilter) {
            case 'TODAY':
                $query->whereDate('pickup_time', $today);
                break;
            case 'UPCOMING':
                $query->whereDate('pickup_time', '>', $today);
                break;
            case 'ACTIVE':
                $query->whereNotIn('status', ['COMPLETED', 'CANCELLED']);
                break;
            case 'COMPLETED':
                $query->where('status', 'COMPLETED');
                break;
            case 'CANCELLED':
                $query->where('status', 'CANCELLED');
                break;
        }

        return view('livewire.admin.orders.index', [
            'orders' => $query->paginate(25)
        ])->title('Takeaway Orders - Vanniyan Admin');
    }
}
