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

        $clean = fn($value) => str_replace(["\r", "\n"], '', trim($value));

        /*
        |--------------------------------------------------------------------------
        | 🔒 SECURITY HEADERS (BALANCED)
        |--------------------------------------------------------------------------
        */
        $headers = [

            // Clickjacking protection
            'X-Frame-Options' => 'SAMEORIGIN',

            // MIME sniffing protection
            'X-Content-Type-Options' => 'nosniff',

            // XSS protection
            'X-XSS-Protection' => '1; mode=block',

            // Referrer policy
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // Permissions
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(self)',

            // HTTPS only
            'Strict-Transport-Security' => $request->isSecure()
                ? 'max-age=31536000; includeSubDomains; preload'
                : '',
        ];

        /*
        |--------------------------------------------------------------------------
        | 🌐 CONTENT SECURITY POLICY (STABLE VERSION)
        |--------------------------------------------------------------------------
        */
        $csp = "
            default-src 'self';

            base-uri 'self';
            form-action 'self';
            frame-ancestors 'self';
            object-src 'none';

            img-src 'self' data: https:;

            font-src 'self' https://fonts.gstatic.com;

            connect-src 'self' https:;

            script-src 'self'
                'unsafe-inline'
                https://cdnjs.cloudflare.com
                https://ajax.googleapis.com
                https://www.gstatic.com;

            style-src 'self'
                'unsafe-inline'
                https://fonts.googleapis.com
                https://cdnjs.cloudflare.com;

            frame-src https://www.google.com https://maps.google.com;

            upgrade-insecure-requests;
        ";

        $headers['Content-Security-Policy'] = preg_replace('/\s+/', ' ', trim($csp));

        /*
        |--------------------------------------------------------------------------
        | ⚡ SMART CACHE (MOST IMPORTANT FOR PERFORMANCE)
        |--------------------------------------------------------------------------
        */
        if (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard') ||
            $request->is('profile')
        ) {
            // 🔴 Sensitive pages
            $headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0';
            $headers['Pragma'] = 'no-cache';
            $headers['Expires'] = '0';

        } elseif (
            $request->is('backend/assets/*') ||
            $request->is('build/*')
        ) {
            // 🟢 Static assets (VERY FAST)
            $headers['Cache-Control'] = 'public, max-age=31536000, immutable';

        } else {
            // 🟡 Public pages (BALANCED)
            $headers['Cache-Control'] = 'public, max-age=86400';
        }

        /*
        |--------------------------------------------------------------------------
        | 🤖 SEO CONTROL
        |--------------------------------------------------------------------------
        */
        if (
            $request->is('admin/*') ||
            $request->is('login') ||
            $request->is('register') ||
            $request->is('dashboard')
        ) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        } else {
            $headers['X-Robots-Tag'] = 'index, follow';
        }

        /*
        |--------------------------------------------------------------------------
        | 🚀 PERFORMANCE BOOST
        |--------------------------------------------------------------------------
        */
        $headers['X-DNS-Prefetch-Control'] = 'on';

        /*
        |--------------------------------------------------------------------------
        | 🔐 APPLY HEADERS
        |--------------------------------------------------------------------------
        */
        foreach ($headers as $key => $value) {
            if (!empty($value)) {
                $response->headers->set($key, $clean($value));
            }
        }

        return $response;
    }
}