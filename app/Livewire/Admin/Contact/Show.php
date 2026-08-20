<?php

namespace App\Livewire\Admin\Contact;

use App\Models\ContactMessage;
use App\Models\AuditLog;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.admin')]
class Show extends Component
{
    public ContactMessage $message;
    public $adminNote;

    public function mount($id)
    {
        $this->message = ContactMessage::findOrFail($id);
        $this->adminNote = $this->message->admin_note;

        // Auto-mark as read if it's new
        if ($this->message->status === 'new') {
            $this->message->status = 'read';
            $this->message->save();

            AuditLog::log(
                Auth::guard('admin')->user(),
                'contact_message_read',
                "Contact Message from {$this->message->name} marked as read",
                ['message_id' => $this->message->id]
            );
        }
    }

    public function updateStatus($status)
    {
        $validStatuses = ['new', 'read', 'replied', 'archived'];
        if (!in_array($status, $validStatuses)) {
            return;
        }

        $oldStatus = $this->message->status;
        $this->message->status = $status;
        $this->message->save();

        AuditLog::log(
            Auth::guard('admin')->user(),
            'contact_message_status_updated',
            "Contact Message status updated from {$oldStatus} to {$status}",
            ['message_id' => $this->message->id, 'old' => $oldStatus, 'new' => $status]
        );

        session()->flash('success', 'Status updated successfully.');
    }

    public function saveAdminNote()
    {
        $this->message->admin_note = $this->adminNote;
        $this->message->save();

        AuditLog::log(
            Auth::guard('admin')->user(),
            'contact_message_note_updated',
            "Admin note updated for contact message from {$this->message->name}",
            ['message_id' => $this->message->id]
        );

        session()->flash('success', 'Admin note saved successfully.');
    }

    public function render()
    {
        return view('livewire.admin.contact.show')->title("Message from {$this->message->name} - Vanniyan Admin");
    }
}
