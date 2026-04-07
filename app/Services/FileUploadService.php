<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /*
    |--------------------------------------------------------------------------
    | UNIVERSAL FILE UPLOAD (Optimized)
    |--------------------------------------------------------------------------
    */
    public function upload($file, ?string $folder = null, $model = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // ✅ Auto folder from model
        if (!$folder && $model) {
            $folder = $this->generateFolderFromModel($model);
        }

        $folder = $folder ?? 'uploads';

        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        /*
        |--------------------------------------------------------------------------
        | 🎬 VIDEO (MP4 OPTIMIZATION)
        |--------------------------------------------------------------------------
        */
        if ($mime === 'video/mp4') {

            $filename = time() . '_' . uniqid() . '.mp4';
            $relativePath = $folder . '/' . $filename;
            $fullPath = storage_path('app/public/' . $relativePath);

            // Ensure folder exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            $tempPath = $file->getRealPath();

            // ✅ Optimized FFmpeg command
            $command = "ffmpeg -i {$tempPath} -vcodec libx264 -crf 28 -preset fast -vf scale=1280:-2 -acodec aac -b:a 128k -movflags +faststart {$fullPath} 2>&1";

            exec($command);

            return $relativePath;
        }

        /*
        |--------------------------------------------------------------------------
        | 🎯 IMAGE HANDLING
        |--------------------------------------------------------------------------
        */

        // ⚠️ GIF → Direct Upload (not recommended)
        if ($mime === 'image/gif') {
            $filename = time() . '_' . uniqid() . '.gif';

            Storage::disk('public')->putFileAs($folder, $file, $filename);

            return $folder . '/' . $filename;
        }

        // ✅ JPG / PNG / WEBP → Resize + Convert to WEBP
        if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {

            $filename = time() . '_' . uniqid() . '.webp';
            $filePath = $folder . '/' . $filename;

            switch ($mime) {
                case 'image/jpeg':
                    $image = @imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($file->getRealPath());
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($file->getRealPath());
                    break;
                default:
                    return null;
            }

            if (!$image) {
                return null;
            }

            // ✅ Resize logic (Max width = 800px)
            $width = imagesx($image);
            $height = imagesy($image);

            $maxWidth = 800;

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor($height * ($newWidth / $width));

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled(
                    $resized,
                    $image,
                    0, 0, 0, 0,
                    $newWidth,
                    $newHeight,
                    $width,
                    $height
                );

                $image = $resized;
            }

            // ✅ Convert to WEBP (compressed)
            ob_start();
            imagewebp($image, null, 75);
            $webpData = ob_get_clean();

            Storage::disk('public')->put($filePath, $webpData);

            return $filePath;
        }

        /*
        |--------------------------------------------------------------------------
        | 📁 OTHER FILES (PDF, DOC, ZIP, etc.)
        |--------------------------------------------------------------------------
        */
        $filename = time() . '_' . uniqid() . '.' . $extension;

        Storage::disk('public')->putFileAs($folder, $file, $filename);

        return $folder . '/' . $filename;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE FILE
    |--------------------------------------------------------------------------
    */
    public function delete(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REPLACE FILE
    |--------------------------------------------------------------------------
    */
    public function replace($file, ?string $oldFilePath, ?string $folder = null, $model = null): ?string
    {
        if ($file) {
            $this->delete($oldFilePath);
            return $this->upload($file, $folder, $model);
        }

        return $oldFilePath;
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE FOLDER FROM MODEL
    |--------------------------------------------------------------------------
    */
    private function generateFolderFromModel($model): string
    {
        $className = class_basename($model);

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $className));
    }
}