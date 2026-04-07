<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Throwable;
use App\Models\ImageMeta;

class ImageOptimizationService
{
    protected float $startedAt = 0.0;

    protected int $maxImagesToAnalyze = 60;
    protected int $maxStringLength = 500;
    protected float $maxExecutionSeconds = 0.1;

    protected ?string $cdnBaseUrl = null;

    public function __construct(
        protected ImageLearningService $learningService,
        protected ImageOptimizationLogger $logger,
        protected ImageSizeDetector $sizeDetector
    ) {
        $this->cdnBaseUrl = config('app.image_cdn_url');
    }

    public function optimize(string $html, Request $request): string
    {
        $this->startedAt = microtime(true);

        try {
            $routePath = '/' . trim($request->path(), '/');
            $requestId = (string) Str::uuid();
            $position = 0;

            $supportsWebp = str_contains($request->header('Accept', ''), 'image/webp');

            return preg_replace_callback('/<img[^>]+>/i', function ($matches) use (
                &$position,
                $routePath,
                $request,
                $requestId,
                $supportsWebp
            ) {

                try {

                    if ($this->timeBudgetExceeded()) return $matches[0];

                    $position++;
                    if ($position > $this->maxImagesToAnalyze) return $matches[0];

                    $imgTag = $matches[0];

                    // ===== SRC =====
                    preg_match('/src=["\']([^"\']+)["\']/', $imgTag, $srcMatch);
                    $src = $srcMatch[1] ?? '';
                    if (!$src) return $imgTag;

                    $parsedPath = parse_url($src, PHP_URL_PATH);
                    if ($parsedPath) $src = $parsedPath;

                    // ===== SKIP =====
                    if (
                        str_starts_with($src, 'http') ||
                        str_starts_with($src, '//') ||
                        str_starts_with($src, 'data:')
                    ) return $imgTag;

                    if (
                        str_contains($imgTag, 'data-no-optimize') ||
                        str_contains($imgTag, 'loading=') ||
                        str_contains($imgTag, 'fetchpriority=')
                    ) return $imgTag;

                    // ===== ATTRIBUTES =====
                    preg_match('/alt=["\']([^"\']*)["\']/', $imgTag, $altMatch);
                    preg_match('/class=["\']([^"\']*)["\']/', $imgTag, $classMatch);
                    preg_match('/id=["\']([^"\']*)["\']/', $imgTag, $idMatch);
                    preg_match('/width=["\']([^"\']*)["\']/', $imgTag, $widthMatch);
                    preg_match('/height=["\']([^"\']*)["\']/', $imgTag, $heightMatch);

                    $imageAlt = $altMatch[1] ?? '';
                    $imageClass = $classMatch[1] ?? '';
                    $imageId = $idMatch[1] ?? '';
                    $imageWidth = $widthMatch[1] ?? null;
                    $imageHeight = $heightMatch[1] ?? null;

                    $context = strtolower($imgTag);

                    // ===== ROLE =====
                    $imageRole = 'content';
                    if (str_contains($context, 'hero')) $imageRole = 'hero';
                    elseif (str_contains($context, 'logo')) $imageRole = 'logo';
                    elseif (str_contains($context, 'product')) $imageRole = 'product';
                    elseif (str_contains($context, 'icon')) $imageRole = 'icon';

                    // ===== AI SCORE =====
                    $score = 0;
                    $confidence = 0;
                    $reasons = [];

                    if ($position === 1) {
                        $score += 50;
                        $confidence += 30;
                        $reasons[] = 'LCP candidate';
                    }

                    if ($imageRole === 'hero') {
                        $score += 40;
                        $confidence += 25;
                        $reasons[] = 'Hero image';
                    }

                    if ($imageRole === 'product') {
                        $score += 25;
                        $confidence += 15;
                    }

                    if ($imageRole === 'logo') {
                        $score += 20;
                        $confidence += 10;
                    }

                    if ($imageRole === 'icon') {
                        $score -= 25;
                    }

                    if ($position <= 3) $score += 15;
                    if ($position >= 10) $score -= 10;

                    // ===== LEARNING =====
                    $adaptive = $this->learningService->getAdaptiveBoost($routePath, $context, $position);
                    $score += $adaptive['boost'];
                    $confidence += $adaptive['confidence'];
                    $reasons = array_merge($reasons, $adaptive['reasons']);

                    // ===== PRIORITY =====
                    $loading = 'lazy';
                    $fetchpriority = '';
                    $mode = 'deferred';

                    if ($score >= 80) {
                        $loading = 'eager';
                        $fetchpriority = 'high';
                        $mode = 'critical';
                    } elseif ($score >= 40) {
                        $loading = 'eager';
                        $fetchpriority = 'auto';
                        $mode = 'important';
                    }

                    // ===== ORIGINAL PATH =====
                    $originalPath = $src;

                    // ===== CLEAN PATH BEFORE ANY CDN / WEBP =====
                    $cleanPath = parse_url($originalPath, PHP_URL_PATH);
                    $cleanPath = ltrim($cleanPath, '/');

                    // ===== META RESOLVE (🔥 FINAL FIX) =====
                    $metaId = null;

                    // ===== 1. SIZE DETECTOR =====
                    $size = $this->sizeDetector->getSize($cleanPath);

                    if ($size && !empty($size['meta'])) {
                        $metaId = $size['meta']->id;
                    }

                    // ===== 2. EXACT MATCH =====
                    if (!$metaId) {
                        $meta = ImageMeta::where('path', $cleanPath)->first();
                        if ($meta) {
                            $metaId = $meta->id;
                        }
                    }

                    // ===== 3. EXTENSION-AGNOSTIC / FUZZY MATCH =====
                    if (!$metaId) {
                        $filename = pathinfo($cleanPath, PATHINFO_FILENAME);

                        $meta = ImageMeta::where('path', 'LIKE', '%' . $filename . '%')
                            ->orderByDesc('id')
                            ->first();

                        if ($meta) {
                            $metaId = $meta->id;
                        }
                    }

                    // ===== 4. ULTRA FALLBACK =====
                    if (!$metaId && $size && !empty($size['meta']->id)) {
                        $metaId = $size['meta']->id;
                    }

                    // ===== APPLY WIDTH/HEIGHT IF NOT PRESENT =====
                    if (!$imageWidth || !$imageHeight) {
                        if ($size && $size['width'] && $size['height']) {
                            $imgTag = str_replace(
                                '<img',
                                '<img width="'.$size['width'].'" height="'.$size['height'].'"',
                                $imgTag
                            );
                        } else {
                            if (!str_contains($imgTag, 'style=')) {
                                $imgTag = str_replace(
                                    '<img',
                                    '<img style="height:auto; max-width:100%;"',
                                    $imgTag
                                );
                            }
                        }
                    }

                    // ===== CDN & WEBP AFTER META RESOLVE =====
                    if ($this->cdnBaseUrl) {
                        $src = rtrim($this->cdnBaseUrl, '/') . '/' . ltrim($cleanPath, '/');
                    }

                    // ===== WEBP =====
                    if ($supportsWebp) {
                        $src = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
                    }

                    // ===== UPDATE IMG TAG SRC =====
                    $imgTag = preg_replace('/src=["\']([^"\']+)["\']/', 'src="'.$src.'"', $imgTag);

                    // ===== SIZE APPLY =====
                    if (!$imageWidth || !$imageHeight) {

                        if ($size && $size['width'] && $size['height']) {

                            $imgTag = str_replace(
                                '<img',
                                '<img width="'.$size['width'].'" height="'.$size['height'].'"',
                                $imgTag
                            );

                        } else {

                            if (!str_contains($imgTag, 'style=')) {
                                $imgTag = str_replace(
                                    '<img',
                                    '<img style="height:auto; max-width:100%;"',
                                    $imgTag
                                );
                            }
                        }
                    }

                    // ===== CDN =====
                    if ($this->cdnBaseUrl) {
                        $src = rtrim($this->cdnBaseUrl, '/') . '/' . ltrim($src, '/');
                    }

                    // ===== WEBP =====
                    if ($supportsWebp) {
                        $src = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
                    }

                    $imgTag = preg_replace('/src=["\']([^"\']+)["\']/', 'src="'.$src.'"', $imgTag);

                    // ===== ATTRIBUTES =====
                    if (!str_contains($imgTag, 'decoding=')) {
                        $imgTag = str_replace('<img', '<img decoding="async"', $imgTag);
                    }

                    if (!str_contains($imgTag, 'loading=')) {
                        $imgTag = str_replace('<img', '<img loading="'.$loading.'"', $imgTag);
                    }

                    if ($fetchpriority && !str_contains($imgTag, 'fetchpriority=')) {
                        $imgTag = str_replace('<img', '<img fetchpriority="'.$fetchpriority.'"', $imgTag);
                    }

                    // ===== LOGGER =====
                    $this->logger->push([
                        'request_id' => $requestId,
                        'route_path' => $routePath,
                        'full_url' => $request->fullUrl(),
                        'http_method' => $request->method(),

                        'image_position' => $position,

                        // store exact path as in ImageMeta
                        'image_src' => $size['meta'] ? $size['meta']->path : $src, 

                        'image_alt' => $this->sanitizeString($imageAlt),
                        'image_class' => $this->sanitizeString($imageClass),
                        'image_id' => $this->sanitizeString($imageId),
                        'image_width' => $imageWidth,
                        'image_height' => $imageHeight,
                        'image_role' => $imageRole,
                        'image_meta_id' => $metaId,
                        'status' => 'optimized',
                        'mode' => $mode,
                        'score' => $score,
                        'confidence' => $confidence >= 60 ? 'high' : ($confidence >= 30 ? 'medium' : 'low'),

                        'loading_value' => $loading,
                        'fetchpriority_value' => $fetchpriority,
                        'decoding_value' => 'async',

                        'reasons' => $this->sanitizeReasons($reasons),

                        'context_payload' => [
                            'route_path' => $routePath,
                            'position' => $position,
                        ],
                    ]);

                    return $imgTag;

                } catch (\Throwable $e) {
                    logger()->error($e->getMessage());
                    return $matches[0];
                }

            }, $html);

        } catch (Throwable) {
            return $html;
        }
    }

    protected function sanitizeReasons(array $reasons): array
    {
        return collect($reasons)
            ->map(fn ($r) => $this->sanitizeString($r, 180))
            ->filter()
            ->take(10)
            ->values()
            ->all();
    }

    protected function sanitizeString(?string $value, ?int $limit = null): string
    {
        $value = $value ?? '';
        $value = strip_tags($value);
        $value = preg_replace('/\s+/', ' ', $value) ?? '';
        $value = trim($value);

        return Str::limit($value, $limit ?? $this->maxStringLength, '');
    }

    protected function timeBudgetExceeded(): bool
    {
        return (microtime(true) - $this->startedAt) >= $this->maxExecutionSeconds;
    }
}