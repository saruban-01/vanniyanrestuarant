<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->getPathInfo(), '/');
        if (empty($path)) {
            $path = '/';
        }

        $canonical = config('app.canonical_host', 'lightuplanka.org');
        $allowed = [$canonical];

        $isLocal = in_array($request->getHost(), ['localhost', '127.0.0.1', '::1'], true);

        if (! $isLocal && ! in_array($request->getHost(), $allowed, true)) {
            return redirect()->away('https://' . $canonical . '/' . $path, 301);
        }

        $redirect = \App\Models\Redirect::where('old_path', $path)
                        ->orWhere('old_path', '/' . $path)
                        ->where('is_active', true)
                        ->first();

        if ($redirect) {
            return redirect($redirect->new_path, $redirect->status_code);
        }

        return $next($request);
    }
}
