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

            if (!is_string($html) || trim($html) === '') {
                return $response;
            }

            // React / Vue / Livewire protection
            if (
                str_contains($html, 'wire:') ||
                str_contains($html, 'data-reactroot') ||
                str_contains($html, 'id="app"')
            ) {
                return $response;
            }

            if (!str_contains($html, '<img')) {
                return $response;
            }

            $optimizedHtml = $this->optimizationService->optimize($html, $request);

            if (is_string($optimizedHtml) && $optimizedHtml !== '' && $optimizedHtml !== $html) {
                $response->setContent($optimizedHtml);
            }

            // Browser caching headers
            $response->headers->set('Cache-Control', 'public, max-age=600');

            $this->logger->persist($request);
        } catch (Throwable $e) {
            $this->logger->logSystemFailure($request, $e);
            return $response;
        }

        return $response;
    }

    protected function shouldRun(Request $request, Response $response): bool
    {
        if (!$request->isMethod('GET')) return false;

        if ($request->ajax() || $request->expectsJson() || $request->isJson()) return false;

        if ($request->is('admin*') || $request->is('api*')) return false;

        if ($request->is('login') || $request->is('register')) return false;

        if ($request->is('password/*') || $request->is('sanctum/*')) return false;

        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains((string) $contentType, 'text/html')) return false;

        return true;
    }
}