<?php

namespace App\Livewire\Admin\Orders;

use App\Models\TakeawayOrder;
use App\Models\AuditLog;
use App\Services\OrderStatusService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public TakeawayOrder $order;
    
    public $adminNote;
    public $cancellationReason;
    public $showCancelModal = false;

    public function mount($reference)
    {
        $this->order = TakeawayOrder::with('items')->where('reference', $reference)->firstOrFail();
        $this->adminNote = $this->order->admin_note;
    }

    public function updateStatus($newStatus, OrderStatusService $statusService)
    {
        if (!$statusService->isValidTransition($this->order->status, $newStatus)) {
            session()->flash('error', "Invalid status transition from {$this->order->status} to {$newStatus}.");
            return;
        }

        $oldStatus = $this->order->status;
        $this->order->status = $newStatus;
        $this->order->save();

        // Log
        AuditLog::log(
            Auth::guard('admin')->user(),
            'takeaway_order_status_updated',
            "Takeaway order {$this->order->reference} status updated from {$oldStatus} to {$newStatus}",
            ['order_id' => $this->order->id, 'reference' => $this->order->reference, 'old' => $oldStatus, 'new' => $newStatus]
        );

        session()->flash('success', 'Order status updated successfully.');
    }

    public function cancelOrder()
    {
        $this->validate([
            'cancellationReason' => 'required|string|max:255'
        ]);

        $oldStatus = $this->order->status;
        $this->order->status = OrderStatusService::STATUS_CANCELLED;
        $this->order->cancellation_reason = $this->cancellationReason;
        $this->order->save();

        AuditLog::log(
            Auth::guard('admin')->user(),
            'takeaway_order_cancelled',
            "Takeaway order {$this->order->reference} cancelled. Reason: {$this->cancellationReason}",
            ['order_id' => $this->order->id, 'reference' => $this->order->reference, 'reason' => $this->cancellationReason]
        );

        $this->showCancelModal = false;
        session()->flash('success', 'Order has been cancelled.');
    }

    public function saveAdminNote()
    {
        $this->order->admin_note = $this->adminNote;
        $this->order->save();

        AuditLog::log(
            Auth::guard('admin')->user(),
            'takeaway_order_note_updated',
            "Takeaway order {$this->order->reference} admin note updated",
            ['order_id' => $this->order->id]
        );

        session()->flash('success', 'Admin note saved successfully.');
    }

    public function render(OrderStatusService $statusService)
    {
        return view('livewire.admin.orders.show', [
            'timelineStatuses' => $statusService->getTimelineStatuses(),
            'statusService' => $statusService,
        ])->title("Order {$this->order->reference} - Vanniyan Admin");
    }
}
