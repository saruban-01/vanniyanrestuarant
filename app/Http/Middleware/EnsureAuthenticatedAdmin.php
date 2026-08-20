<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticatedAdmin
{
    /**
     * Handle an incoming request.
     *
     * - Guests are sent to the (configurable) admin login page.
     * - Deactivated admins are signed out and sent to the login page.
     * - Only active, authenticated admins may continue.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('admin');

        if (! $guard->check()) {
            return redirect()->guest(route('admin.login'));
        }

        if (! $guard->user()->is_active) {
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}