<?php

use App\Http\Middleware\OptimizeImagesMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\PreventCitizenBackHistoryMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            PreventBackHistoryMiddleware::class,
            PreventCitizenBackHistoryMiddleware::class,
            OptimizeImagesMiddleware::class,
            RedirectIfAuthenticatedCustom::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->report(function (\Throwable $e) {
            if (
                $e instanceof ValidationException ||
                $e instanceof AuthenticationException ||
                $e instanceof NotFoundHttpException ||
                $e instanceof AccessDeniedHttpException ||
                $e instanceof TokenMismatchException
            ) {
                return false;
            }

            Log::error('Application Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        });

        $exceptions->render(function (\Throwable $e, Request $request) {

            // Validation errors -> Laravel default handling
            if ($e instanceof ValidationException) {
                return null;
            }

            // Unauthenticated user trying to access protected page -> redirect to login with message
            if ($e instanceof AuthenticationException) {
                return redirect()
                    ->route('admin.login')
                    ->with('warning', 'Please login first to access this page.');
            }

            // Route not found
            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }

            // Forbidden access
            if ($e instanceof AccessDeniedHttpException) {
                return response()->view('errors.403', [], 403);
            }

            // CSRF token mismatch / session expired -> redirect to login with message
            if ($e instanceof TokenMismatchException) {
                return redirect()
                    ->route('admin.login')
                    ->with('warning', 'Your session has expired. Please login again.');
            }

            // API requests
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong.',
                ], 500);
            }

            // Web requests
            return response()->view('errors.500', [], 500);
        });
    })

    ->create();