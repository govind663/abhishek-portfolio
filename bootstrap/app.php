<?php

use App\Http\Middleware\OptimizeImagesMiddleware;
use App\Http\Middleware\PreventBackHistoryMiddleware;
use App\Http\Middleware\PreventCitizenBackHistoryMiddleware;
use App\Http\Middleware\RedirectIfAuthenticatedCustom;
use App\Http\Middleware\ResumeStepLockMiddleware;

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

            // Laravel core
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // Custom global middleware
            PreventBackHistoryMiddleware::class,
            PreventCitizenBackHistoryMiddleware::class,
            OptimizeImagesMiddleware::class,
            RedirectIfAuthenticatedCustom::class,

            /**
             * ⚠️ IMPORTANT FIX:
             * ResumeStepLockMiddleware ko GLOBAL mat lagao
             * warna every request pe run hoga (bug + blocking issue)
             */
        ]);

        /**
         * ✅ REGISTER AS ALIAS (CORRECT WAY)
         * use only on resume routes
         */
        $middleware->alias([
            'resume.lock' => ResumeStepLockMiddleware::class,
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

            if ($e instanceof ValidationException) {
                return null;
            }

            if ($e instanceof AuthenticationException) {
                return redirect()
                    ->route('admin.login')
                    ->with('warning', 'Please login first to access this page.');
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->view('errors.404', [], 404);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return response()->view('errors.403', [], 403);
            }

            if ($e instanceof TokenMismatchException) {
                return redirect()
                    ->route('admin.login')
                    ->with('warning', 'Session expired. Please login again.');
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Something went wrong.',
                ], 500);
            }

            return response()->view('errors.500', [], 500);
        });
    })

    ->create();