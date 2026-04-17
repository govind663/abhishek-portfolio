<?php

namespace App\Http\Middleware;

use App\Services\ImageOptimizationLogger;
use App\Services\ImageOptimizationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OptimizeImagesMiddleware
{
    public function __construct(
        protected ImageOptimizationService $optimizationService,
        protected ImageOptimizationLogger $logger
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            if (!$this->shouldRun($request, $response)) {
                return $response;
            }

            $html = $response->getContent();

            // ✅ Basic validation
            if (!is_string($html) || trim($html) === '') {
                return $response;
            }

            // 🚫 Skip large HTML (performance protection)
            if (strlen($html) > 300000) {
                return $response;
            }

            // 🚫 Skip modern frameworks (important)
            if (
                str_contains($html, 'wire:') ||
                str_contains($html, 'data-reactroot') ||
                str_contains($html, 'id="app"') ||
                str_contains($html, 'x-data')
            ) {
                return $response;
            }

            // 🚫 Skip if no images (case-insensitive)
            if (stripos($html, '<img') === false) {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | 🔥 SAFE Optimization (with fallback)
            |--------------------------------------------------------------------------
            */
            $originalHtml = $html;

            $optimizedHtml = $this->optimizationService->optimize($html, $request);

            // ✅ STRICT VALIDATION (UI break prevention)
            if (
                is_string($optimizedHtml) &&
                $optimizedHtml !== '' &&
                strlen($optimizedHtml) > 1000 &&
                str_contains($optimizedHtml, '<html') &&
                str_contains($optimizedHtml, '</html>')
            ) {
                $response->setContent($optimizedHtml);
            } else {
                // ❌ fallback to original
                $response->setContent($originalHtml);
            }

            /*
            |--------------------------------------------------------------------------
            | ⚠️ Cache Header (NO CONFLICT)
            |--------------------------------------------------------------------------
            */
            if (
                !$response->headers->has('Cache-Control') &&
                !$request->is('admin*')
            ) {
                $response->headers->set('Cache-Control', 'public, max-age=300');
            }

            /*
            |--------------------------------------------------------------------------
            | 🧾 Logging (safe)
            |--------------------------------------------------------------------------
            */
            $this->logger->persist($request);

        } catch (Throwable $e) {

            // ❌ NEVER break UI
            $this->logger->logSystemFailure($request, $e);

            return $response;
        }

        return $response;
    }

    protected function shouldRun(Request $request, Response $response): bool
    {
        // ✅ Only GET requests
        if (!$request->isMethod('GET')) return false;

        // ❌ Skip AJAX / API / JSON
        if ($request->ajax() || $request->expectsJson() || $request->isJson()) return false;

        // ❌ Skip admin + API
        if ($request->is('admin*') || $request->is('api*')) return false;

        // ❌ Skip auth pages
        if ($request->is('login') || $request->is('register')) return false;

        if ($request->is('password/*') || $request->is('sanctum/*')) return false;

        // ✅ Only HTML response
        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains((string) $contentType, 'text/html')) return false;

        return true;
    }
}