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

        $clean = fn($value) => str_replace(["\r", "\n"], '', $value);

        $headers = [

            // 🔒 Security Headers
            'X-Frame-Options' => 'DENY',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // 🌐 Content Security Policy
            'Content-Security-Policy' => "
                default-src 'self';
                img-src 'self' data: http: https:;
                script-src 'self' 'unsafe-inline' 'unsafe-eval' 
                    http://cdnjs.cloudflare.com
                    https://cdnjs.cloudflare.com
                    http://ajax.googleapis.com
                    https://ajax.googleapis.com;
                style-src 'self' 'unsafe-inline' 
                    http://fonts.googleapis.com
                    https://fonts.googleapis.com
                    http://cdnjs.cloudflare.com
                    https://cdnjs.cloudflare.com;
                font-src 'self' 
                    http://fonts.gstatic.com
                    https://fonts.gstatic.com
                    data:;
                connect-src 'self' 
                    http://cdnjs.cloudflare.com
                    https://cdnjs.cloudflare.com;
                frame-src 'self';
            ",

            // 🔐 HTTPS Security
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        ];

        /*
        |--------------------------------------------------------------------------
        | ⚡ FIX ADDITION (SAFE MERGE - NO CHANGE IN YOUR CODE)
        |--------------------------------------------------------------------------
        */
        if (!empty($headers['Content-Security-Policy'])) {
            $headers['Content-Security-Policy'] = preg_replace(
                '/\s+/',
                ' ',
                trim($headers['Content-Security-Policy'])
            );
        }

        // ✅ Cache Strategy
        if ($request->is('backend/assets/*') || $request->is('build/*')) {
            // Static assets → cacheable
            $headers['Cache-Control'] = 'public, max-age=31536000, immutable';
        } else {
            // Dynamic pages → no-cache
            $headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0';
            $headers['Pragma'] = 'no-cache';
            $headers['Expires'] = '0';
        }

        // Apply headers
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $clean($value));
        }

        return $response;
    }
}