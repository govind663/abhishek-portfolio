<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ===== Services
use App\Services\UserHistoryService;
use App\Services\ImageLearningService;
use App\Services\ImageOptimizationLogger;
use App\Services\ImageOptimizationService;
use App\Services\ImageSizeDetector;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Core Business Services
        |--------------------------------------------------------------------------
        */

        // ===== User History Service
        $this->app->singleton(UserHistoryService::class, function ($app) {
            return new UserHistoryService();
        });


        /*
        |--------------------------------------------------------------------------
        | Image Optimization Services
        |--------------------------------------------------------------------------
        */

        // Logger
        $this->app->singleton(ImageOptimizationLogger::class, function ($app) {
            return new ImageOptimizationLogger();
        });

        // Learning Service
        $this->app->singleton(ImageLearningService::class, function ($app) {
            return new ImageLearningService();
        });

        // Size Detector (Auto-resolve if no dependency, optional binding)
        $this->app->singleton(ImageSizeDetector::class, function ($app) {
            return new ImageSizeDetector();
        });

        // Main Optimization Service (Dependency Injection)
        $this->app->singleton(ImageOptimizationService::class, function ($app) {
            return new ImageOptimizationService(
                $app->make(ImageLearningService::class),
                $app->make(ImageOptimizationLogger::class),
                $app->make(ImageSizeDetector::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}