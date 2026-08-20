<?php

namespace App\Livewire\Admin\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Security extends Component
{
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($this->current_password, $admin->password)) {
            $this->addError('current_password', 'The provided password does not match your current password.');
            return;
        }

        $admin->update([
            'password' => Hash::make($this->new_password),
            'password_changed_at' => now(),
        ]);

        \App\Models\AuditLog::create([
            'action' => 'PASSWORD_CHANGED',
            'module' => 'SYSTEM',
            'description' => 'System Administrator changed their password',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('success', 'Your password has been changed successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile.security', [
            'admin' => Auth::guard('admin')->user(),
        ])->title('Security Profile - Vanniyan Admin');
    }
}
