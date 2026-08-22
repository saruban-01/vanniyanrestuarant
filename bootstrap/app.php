<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Only trust the real upstream proxy IPs. Prevents client-supplied
        // X-Forwarded-For spoofing (which would bypass login/contact throttles).
        $middleware->trustProxies(at: explode(',', env('TRUSTED_PROXIES', '127.0.0.1')));

        $middleware->append(\App\Http\Middleware\SecureHeadersMiddleware::class);
        $middleware->append(\App\Http\Middleware\RedirectMiddleware::class);

        // Where guests are sent when they hit a protected route.
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            $adminPath = config('admin.path');

            if ($request->is($adminPath) || $request->is($adminPath.'/*')) {
                return route('admin.login');
            }

            return route('home');
        });

        // Authenticated admins visiting the login page go straight to the dashboard
        // (previously they were bounced to the public homepage).
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
