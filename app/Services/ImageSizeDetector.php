<?php

namespace App\Services;

use App\Models\ImageMeta;
use App\Models\ImageOptimizationLog;
use Illuminate\Support\Facades\Cache;

class ImageSizeDetector
{
    public function getSize(string $path): ?array
    {
        try {
            // ✅ CLEAN PATH
            $path = parse_url($path, PHP_URL_PATH);
            $path = ltrim($path, '/');

            // ===== 1. DB CHECK (PRIMARY + FALLBACK) =====
            $meta = ImageMeta::where('path', $path)
                ->orWhere(function ($q) use ($path) {
                    $q->where('path', 'LIKE', '%' . basename($path));
                })
                ->first();

            if ($meta && $meta->width && $meta->height) {
                // Update logs with exact ImageMeta path
                $this->updateLogs($meta->path, $meta->id);
                return [
                    'width' => $meta->width,
                    'height' => $meta->height,
                    'meta' => $meta
                ];
            }

            // ===== 2. CACHE =====
            return Cache::remember("img_size_" . md5($path), 86400, function () use ($path) {

                $fullPath = public_path($path);

                // ❌ FILE NOT FOUND
                if (!file_exists($fullPath)) {
                    logger()->error('Image not found: ' . $fullPath);
                    return null;
                }

                // ❌ GET IMAGE SIZE FAILED
                $size = @getimagesize($fullPath);
                if (!$size) {
                    logger()->error('getimagesize failed: ' . $fullPath);
                    return null;
                }

                $width = $size[0] ?? null;
                $height = $size[1] ?? null;

                if (!$width || !$height) {
                    logger()->error('Invalid dimensions: ' . $fullPath);
                    return null;
                }

                // ===== 3. EXTRA META =====
                $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                $fileSize = @filesize($fullPath) ?: null;
                $hash = @md5_file($fullPath);

                if (!$hash) {
                    logger()->error('Hash generation failed: ' . $fullPath);
                    return null;
                }

                // ===== 4. SAVE (DUPLICATE SAFE) =====
                $meta = ImageMeta::updateOrCreate(
                    ['hash' => $hash], // ✅ duplicate-safe
                    [
                        'path' => $path,
                        'width' => $width,
                        'height' => $height,
                        'type' => $extension,
                        'file_size' => $fileSize,
                    ]
                );

                // ===== 5. UPDATE LOG TABLE =====
                // Use exact meta path to ensure match
                $this->updateLogs($meta->path, $meta->id);

                return [
                    'width' => $width,
                    'height' => $height,
                    'meta' => $meta
                ];
            });

        } catch (\Throwable $e) {
            logger()->error('ImageSizeDetector Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update image optimization logs with meta_id and log the update
     */
    protected function updateLogs(string $path, int $metaId): void
    {
        // Normalize path: remove leading slash
        $normalizedPath = ltrim($path, '/');

        // Match both exact path or filename anywhere
        $updated = ImageOptimizationLog::where(function($q) use ($normalizedPath) {
            $filename = pathinfo($normalizedPath, PATHINFO_BASENAME);
            $q->where('image_src', $normalizedPath)
            ->orWhere('image_src', 'LIKE', "%$filename%");
        })->update(['image_meta_id' => $metaId]);

        if ($updated) {
            logger()->info("ImageOptimizationLog updated: {$updated} row(s) for path '{$normalizedPath}', meta_id={$metaId}");
        } else {
            logger()->warning("No ImageOptimizationLog rows matched for path '{$normalizedPath}', meta_id={$metaId}");
        }
    }
}