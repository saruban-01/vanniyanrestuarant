<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin-auth')]
class Login extends Component
{
    public $username = '';
    public $password = '';

    public function login()
    {
        $this->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $throttleKey = 'admin_login|' . request()->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->addError('username', "Too many login attempts. Please try again in {$seconds} seconds.");
            return;
        }

        if (Auth::guard('admin')->attempt(['username' => $this->username, 'password' => $this->password, 'is_active' => true])) {
            RateLimiter::clear($throttleKey);
            
            // Session regeneration & Update Last Login
            session()->regenerate();
            $admin = Auth::guard('admin')->user();
            $admin->update(['last_login_at' => now()]);

            // Audit log
            \App\Models\AuditLog::create([
                'action' => 'LOGIN',
                'module' => 'AUTHENTICATION',
                'description' => "System Administrator [{$admin->username}] logged in",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey);

        // Audit failed attempts for security monitoring.
        \App\Models\AuditLog::create([
            'action' => 'LOGIN_FAILED',
            'module' => 'AUTHENTICATION',
            'description' => "Failed admin login attempt for username [{$this->username}]",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $this->addError('username', 'The username or password is incorrect.');
    }

    public function render()
    {
        return view('livewire.admin.auth.login')->title('Admin Login - Vanniyan');
    }
}
