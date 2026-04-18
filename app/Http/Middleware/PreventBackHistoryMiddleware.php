<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistoryMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $clean = fn($value) => str_replace(["\r", "\n"], '', trim($value));

        /*
        |--------------------------------------------------------------------------
        | 🔒 SECURITY HEADERS
        |--------------------------------------------------------------------------
        */
        $headers = [

            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            /*
            |--------------------------------------------------------------------------
            | 🌐 FINAL CSP (ALL FIXED)
            |--------------------------------------------------------------------------
            */
            'Content-Security-Policy' => "
                default-src 'self';

                base-uri 'self';
                form-action 'self';
                frame-ancestors 'self';
                object-src 'none';

                img-src 'self' data: https:;

                font-src 'self' data: https://fonts.gstatic.com;

                connect-src 'self'
                    https://www.google.com
                    https://www.gstatic.com
                    https://maps.googleapis.com;

                script-src 'self'
                    'unsafe-inline'
                    'unsafe-eval'
                    https://cdnjs.cloudflare.com
                    https://ajax.googleapis.com
                    https://www.gstatic.com
                    https://maps.googleapis.com
                    https://www.google.com
                    https://unpkg.com;

                style-src 'self'
                    'unsafe-inline'
                    https://fonts.googleapis.com
                    https://cdnjs.cloudflare.com;

                frame-src 'self'
                    https://www.google.com
                    https://www.gstatic.com
                    https://maps.google.com
                    https://maps.googleapis.com;

                upgrade-insecure-requests;
            ",

            // 🔐 HTTPS सुरक्षा
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        ];

        /*
        |--------------------------------------------------------------------------
        | 🧹 CLEAN CSP (REMOVE EXTRA SPACES)
        |--------------------------------------------------------------------------
        */
        if (!empty($headers['Content-Security-Policy'])) {
            $headers['Content-Security-Policy'] = preg_replace(
                '/\s+/',
                ' ',
                trim($headers['Content-Security-Policy'])
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ⚡ CACHE STRATEGY (SMART)
        |--------------------------------------------------------------------------
        */
        if ($request->is('backend/assets/*') || $request->is('build/*')) {
            // 🟢 Static files → Fast
            $headers['Cache-Control'] = 'public, max-age=31536000, immutable';

        } elseif (
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

        } else {
            // 🟡 Public pages
            $headers['Cache-Control'] = 'public, max-age=86400';
        }

        /*
        |--------------------------------------------------------------------------
        | 🚀 PERFORMANCE BOOST
        |--------------------------------------------------------------------------
        */
        $headers['X-DNS-Prefetch-Control'] = 'on';

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
        | ✅ APPLY HEADERS
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