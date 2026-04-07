<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $guard = 'web')
    {
        // Ensure guard fallback
        $guard = $guard ?? 'web';

        // If user already logged in
        if (Auth::guard($guard)->check()) {

            // Only apply on guest routes
            if ($request->route() && $request->routeIs([
                'admin.login',
                'admin.register',
                'admin.forget-password.request',
                'admin.password.reset'
            ])) {

                return redirect()
                    ->route('admin.dashboard')
                    ->with('message', 'You are already logged in!');
            }
        }

        return $next($request);
    }
}