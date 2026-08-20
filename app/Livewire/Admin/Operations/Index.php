<?php

namespace App\Livewire\Admin\Operations;

use App\Models\TakeawayOrder;
use App\Models\Reservation;
use App\Models\RestaurantSetting;
use App\Models\AuditLog;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.admin')]
class Index extends Component
{
    public $takeawayPaused = false;
    public $reservationsPaused = false;

    public function mount()
    {
        $this->takeawayPaused = RestaurantSetting::where('key', 'takeaway_paused')->value('value') === '1';
        $this->reservationsPaused = RestaurantSetting::where('key', 'reservations_paused')->value('value') === '1';
    }

    public function toggleTakeaway()
    {
        $this->takeawayPaused = !$this->takeawayPaused;
        RestaurantSetting::updateOrCreate(
            ['key' => 'takeaway_paused'],
            ['value' => $this->takeawayPaused ? '1' : '0']
        );

        AuditLog::log(
            Auth::guard('admin')->user(),
            'service_toggled',
            "Takeaway service " . ($this->takeawayPaused ? "PAUSED" : "RESUMED")
        );

        session()->flash('success', 'Takeaway service status updated.');
    }

    public function toggleReservations()
    {
        $this->reservationsPaused = !$this->reservationsPaused;
        RestaurantSetting::updateOrCreate(
            ['key' => 'reservations_paused'],
            ['value' => $this->reservationsPaused ? '1' : '0']
        );

        AuditLog::log(
            Auth::guard('admin')->user(),
            'service_toggled',
            "Reservation service " . ($this->reservationsPaused ? "PAUSED" : "RESUMED")
        );

        session()->flash('success', 'Reservation service status updated.');
    }

    public function updateOrderStatus($orderId, $newStatus)
    {
        $order = TakeawayOrder::find($orderId);
        if ($order) {
            $oldStatus = $order->status;
            $order->status = $newStatus;
            $order->save();

            AuditLog::log(
                Auth::guard('admin')->user(),
                'takeaway_order_status_updated',
                "Order {$order->reference} moved from {$oldStatus} to {$newStatus} via Kanban",
                ['order_id' => $order->id, 'new_status' => $newStatus]
            );
        }
    }

    public function render()
    {
        $today = Carbon::today();

        // Kanban columns
        $pendingOrders = TakeawayOrder::where('status', 'received')->whereDate('created_at', $today)->orderBy('created_at', 'asc')->get();
        $confirmedOrders = TakeawayOrder::where('status', 'confirmed')->whereDate('created_at', $today)->orderBy('created_at', 'asc')->get();
        $completedOrders = TakeawayOrder::where('status', 'completed')->whereDate('created_at', $today)->orderBy('created_at', 'asc')->get();

        // Today's Reservations
        $reservations = Reservation::with('table')
            ->whereDate('reservation_date', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_time', 'asc')
            ->get();

        return view('livewire.admin.operations.index', [
            'pendingOrders' => $pendingOrders,
            'confirmedOrders' => $confirmedOrders,
            'completedOrders' => $completedOrders,
            'reservations' => $reservations,
        ])->title('Daily Operations - Vanniyan Admin');
    }
}
