<?php

namespace App\Services;

use App\Models\ImageOptimizationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ImageOptimizationLogger
{
    protected array $rows = []; 
    protected array $systemNotes = [];

    protected bool $databaseLoggingEnabled = true;
    protected bool $fileLoggingEnabled = true;
    protected int $maxRowsPerRequest = 80;
    protected int $sampleRate = 100; // 100 = log every request, 20 = 20%

    public function push(array $row): void
    {
        if (count($this->rows) >= $this->maxRowsPerRequest) {
            return;
        }

        $this->rows[] = $row;
    }

    public function pushSystemNote(array $note): void
    {
        $this->systemNotes[] = $note;
    }

    public function persist(Request $request): void
    {
        if (!$this->shouldPersist()) {
            $this->reset();
            return;
        }

        try {
            if ($this->databaseLoggingEnabled && !empty($this->rows)) {
                $this->persistToDatabase($request);
            }

            if ($this->fileLoggingEnabled && (!empty($this->rows) || !empty($this->systemNotes))) {
                $this->writeHumanReadableLog($request);
            }
        } catch (Throwable $e) {
            Log::warning('ImageOptimizationLogger persist failed: ' . $e->getMessage());
        } finally {
            $this->reset();
        }
    }

    public function logSystemFailure(Request $request, Throwable $e): void
    {
        try {
            $date = now()->format('Y-m-d');
            $time = now()->format('H-i-s');
            $path = trim(str_replace(['/', '\\'], '-', $request->path()), '-');
            $path = $path !== '' ? $path : 'home';

            $file = "activity/image-brain-errors/{$date}/{$time}-{$path}.log";

            $lines = [
                '========================================',
                'Image Brain System Failure',
                '========================================',
                'Date Time : ' . now()->format('Y-m-d H:i:s'),
                'Route     : /' . ltrim($request->path(), '/'),
                'Method    : ' . $request->method(),
                'Error     : ' . $this->sanitize($e->getMessage(), 500),
                '----------------------------------------',
            ];

            Storage::disk('local')->put($file, implode(PHP_EOL, $lines));
        } catch (Throwable) {
            Log::warning('Unable to write image optimizer failure log.');
        }
    }

    protected function persistToDatabase(Request $request): void
    {
        $userId = Auth::id();

        $insertData = [];

        foreach ($this->rows as $row) {
            $insertData[] = [
                'request_id' => $row['request_id'] ?? null,
                'route_path' => $this->sanitize($row['route_path'] ?? null, 255),
                'full_url' => $this->sanitize($row['full_url'] ?? null, 2048),
                'http_method' => $this->sanitize($row['http_method'] ?? null, 20),
                'image_position' => (int) ($row['image_position'] ?? 0),
                'image_src' => $this->sanitize($row['image_src'] ?? null, 2048),
                'image_alt' => $this->sanitize($row['image_alt'] ?? null, 500),
                'image_class' => $this->sanitize($row['image_class'] ?? null, 500),
                'image_id' => $this->sanitize($row['image_id'] ?? null, 255),
                'image_role' => $this->sanitize($row['image_role'] ?? null, 255),
                'status' => $this->sanitize($row['status'] ?? null, 50),
                'mode' => $this->sanitize($row['mode'] ?? null, 50),
                'score' => (int) ($row['score'] ?? 0),
                'confidence' => $this->sanitize($row['confidence'] ?? null, 20),
                'loading_value' => $this->sanitize($row['loading_value'] ?? null, 20),
                'fetchpriority_value' => $this->sanitize($row['fetchpriority_value'] ?? null, 20),
                'decoding_value' => $this->sanitize($row['decoding_value'] ?? null, 20),
                'image_width' => $row['image_width'] ?? null,
                'image_height' => $row['image_height'] ?? null,
                'reasons' => json_encode($row['reasons'] ?? [], JSON_UNESCAPED_UNICODE),
                'context_payload' => json_encode($row['context_payload'] ?? [], JSON_UNESCAPED_UNICODE),
                'user_agent' => $this->sanitize($request->userAgent(), 1000),
                'ip_address' => $this->sanitize($request->ip(), 45),
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (!empty($insertData)) {
            ImageOptimizationLog::insert($insertData);
        }
    }

    protected function writeHumanReadableLog(Request $request): void
    {
        $date = now()->format('Y-m-d');
        $time = now()->format('H-i-s');
        $path = trim(str_replace(['/', '\\'], '-', $request->path()), '-');
        $path = $path !== '' ? $path : 'home';

        $file = "activity/image-brain/{$date}/{$time}-{$path}-" . Str::random(6) . ".log";

        $lines = [];
        $lines[] = '========================================';
        $lines[] = 'Smart Image Brain Activity';
        $lines[] = '========================================';
        $lines[] = 'Date Time : ' . now()->format('Y-m-d H:i:s');
        $lines[] = 'Route     : /' . ltrim($request->path(), '/');
        $lines[] = 'Method    : ' . $request->method();
        $lines[] = 'Rows      : ' . count($this->rows);
        $lines[] = '----------------------------------------';

        foreach ($this->rows as $index => $row) {
            $lines[] = 'Image #' . ($index + 1);
            $lines[] = 'Position      : ' . ($row['image_position'] ?? 'N/A');
            $lines[] = 'Status        : ' . ($row['status'] ?? 'N/A');
            $lines[] = 'Mode          : ' . ($row['mode'] ?? 'N/A');
            $lines[] = 'Confidence    : ' . ($row['confidence'] ?? 'N/A');
            $lines[] = 'Score         : ' . ($row['score'] ?? 0);
            $lines[] = 'Loading       : ' . ($row['loading_value'] ?? 'N/A');
            $lines[] = 'FetchPriority : ' . ($row['fetchpriority_value'] ?? 'N/A');
            $lines[] = 'Source        : ' . $this->sanitize($row['image_src'] ?? 'N/A', 1000);
            $lines[] = 'Reasons       :';

            foreach (($row['reasons'] ?? []) as $reason) {
                $lines[] = '  - ' . $this->sanitize((string) $reason, 180);
            }

            $lines[] = '----------------------------------------';
        }

        if (!empty($this->systemNotes)) {
            $lines[] = 'System Notes';
            $lines[] = '----------------------------------------';

            foreach ($this->systemNotes as $note) {
                $lines[] = 'Type    : ' . $this->sanitize($note['note_type'] ?? 'N/A', 100);
                $lines[] = 'Message : ' . $this->sanitize($note['message'] ?? 'N/A', 250);
                $lines[] = '----------------------------------------';
            }
        }

        Storage::disk('local')->put($file, implode(PHP_EOL, $lines));
    }

    protected function shouldPersist(): bool
    {
        return random_int(1, 100) <= $this->sampleRate;
    }

    protected function sanitize(?string $value, int $limit = 255): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = strip_tags($value);
        $value = preg_replace('/\s+/', ' ', $value) ?? '';
        $value = trim($value);

        return Str::limit($value, $limit, '');
    }

    protected function reset(): void
    {
        $this->rows = [];
        $this->systemNotes = [];
    }
}