<?php

namespace App\Services;

use App\Models\ImageOptimizationLog;

class ImageLearningService
{
    public function getAdaptiveBoost(string $routePath, string $context, int $position): array
    {
        $boost = 0; 
        $confidence = 0;
        $reasons = [];

        $logs = ImageOptimizationLog::query()
            ->where('route_path', $routePath)
            ->whereNull('deleted_at')
            ->latest('id')
            ->limit(120)
            ->get(['mode', 'score', 'image_position', 'confidence']);

        if ($logs->isEmpty()) {
            return [
                'boost' => 0,
                'confidence' => 0,
                'reasons' => [],
            ];
        }

        $criticalCount = $logs->where('mode', 'critical')->count();
        $importantCount = $logs->where('mode', 'important')->count();
        $deferredCount = $logs->where('mode', 'deferred')->count();

        $nearPositionCount = $logs->filter(function ($log) use ($position) {
            return abs(((int) $log->image_position) - $position) <= 1;
        })->count();

        if ($criticalCount >= 15 && $position <= 2) {
            $boost += 8;
            $confidence += 10;
            $reasons[] = 'Adaptive learning boost from critical route history';
        }

        if ($importantCount >= 25 && $position <= 3) {
            $boost += 5;
            $confidence += 8;
            $reasons[] = 'Adaptive learning boost from important image history';
        }

        if ($deferredCount >= 40 && $position >= 5) {
            $boost -= 7;
            $confidence += 7;
            $reasons[] = 'Adaptive learning penalty from deferred history';
        }

        if ($nearPositionCount >= 10) {
            $boost += 4;
            $confidence += 6;
            $reasons[] = 'Adaptive position pattern matched';
        }

        return [
            'boost' => $boost,
            'confidence' => $confidence,
            'reasons' => $reasons,
        ];
    }
}