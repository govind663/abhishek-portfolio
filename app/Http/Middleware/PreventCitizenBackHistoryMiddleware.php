<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCitizenBackHistoryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /*
        |--------------------------------------------------------------------------
        | 1. Security Headers
        |--------------------------------------------------------------------------
        */
        // ✅ iframe allow (Google Maps ke liye)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ✅ geolocation allow
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=*');

        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Content Security Policy (FINAL FIX)
        |--------------------------------------------------------------------------
        */
        $csp = implode(' ', [
            "default-src 'self';",
            "base-uri 'self';",
            "form-action 'self';",
            "frame-ancestors 'self';",
            "object-src 'none';",

            // Images
            "img-src 'self' data: https://*.google.com https://*.gstatic.com https:;",

            // CSS
            "style-src 'self' 'unsafe-inline' https://*.googleapis.com https:;",

            // JS
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://*.google.com https://*.gstatic.com https:;",

            // Fonts
            "font-src 'self' data: https://*.gstatic.com https:;",

            // API connections
            "connect-src 'self' https://*.googleapis.com https://*.google.com https:;",

            // 🔥 MOST IMPORTANT (Google Maps iframe allow)
            "frame-src 'self' https://*.google.com https://*.google.co.in https://maps.google.com https://maps.googleapis.com https://maps.gstatic.com;",

            "media-src 'self' https:;",
            "upgrade-insecure-requests;"
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        /*
        |--------------------------------------------------------------------------
        | 3. Cache Control
        |--------------------------------------------------------------------------
        */
        if (
            $request->is('admin/*') ||
            $request->is('citizen/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard') ||
            $request->is('profile')
        ) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
        } else {
            $response->headers->set('Cache-Control', 'public, max-age=300, must-revalidate');
        }

        /*
        |--------------------------------------------------------------------------
        | 4. SEO Headers
        |--------------------------------------------------------------------------
        */
        if (
            $request->is('admin/*') ||
            $request->is('citizen/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard')
        ) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
        } else {
            $response->headers->set('X-Robots-Tag', 'index, follow');
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Performance
        |--------------------------------------------------------------------------
        */
        $response->headers->set('X-DNS-Prefetch-Control', 'on');

        return $response;
    }
}