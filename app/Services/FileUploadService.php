<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function upload($file, ?string $folder = null, $model = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        if (!$folder && $model) {
            $folder = $this->generateFolderFromModel($model);
        }

        $folder = $folder ?? 'uploads';

        $mime = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        /*
        |--------------------------------------------------------------------------
        | 🎬 VIDEO (UNCHANGED)
        |--------------------------------------------------------------------------
        */
        if ($mime === 'video/mp4') {

            $filename = time() . '_' . uniqid() . '.mp4';
            $relativePath = $folder . '/' . $filename;
            $fullPath = storage_path('app/public/' . $relativePath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            $tempPath = $file->getRealPath();

            $command = "ffmpeg -i {$tempPath} -vcodec libx264 -crf 28 -preset fast -vf scale=1280:-2 -acodec aac -b:a 128k -movflags +faststart {$fullPath} 2>&1";

            exec($command);

            return $relativePath;
        }

        /*
        |--------------------------------------------------------------------------
        | 🎯 IMAGE HANDLING (UPGRADED)
        |--------------------------------------------------------------------------
        */

        if ($mime === 'image/gif') {
            $filename = time() . '_' . uniqid() . '.gif';
            Storage::disk('public')->putFileAs($folder, $file, $filename);
            return $folder . '/' . $filename;
        }

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

            $width = imagesx($image);
            $height = imagesy($image);

            /*
            |--------------------------------------------------------------------------
            | 🔥 NEW: MULTIPLE SIZES (Responsive Images)
            |--------------------------------------------------------------------------
            */

            $sizes = [150, 300, 600, 800]; // mobile → desktop

            foreach ($sizes as $size) {

                if ($width <= $size) {
                    continue; // skip unnecessary resize
                }

                $newWidth = $size;
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

                ob_start();
                imagewebp($resized, null, 75);
                $webpData = ob_get_clean();

                Storage::disk('public')->put(
                    $folder . '/' . $size . '_' . $filename,
                    $webpData
                );

                // ✅ PHP 8.3 memory safe
                $resized = null;
            }

            /*
            |--------------------------------------------------------------------------
            | ORIGINAL (UNCHANGED LOGIC)
            |--------------------------------------------------------------------------
            */

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

            ob_start();
            imagewebp($image, null, 75);
            $webpData = ob_get_clean();

            Storage::disk('public')->put($filePath, $webpData);

            // ✅ PHP 8.3 cleanup
            $image = null;

            return $filePath; // 👈 SAME RETURN (no breaking change)
        }

        /*
        |--------------------------------------------------------------------------
        | 📁 OTHER FILES (UNCHANGED)
        |--------------------------------------------------------------------------
        */
        $filename = time() . '_' . uniqid() . '.' . $extension;

        Storage::disk('public')->putFileAs($folder, $file, $filename);

        return $folder . '/' . $filename;
    }

    public function delete(?string $filePath): void
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
    }

    public function replace($file, ?string $oldFilePath, ?string $folder = null, $model = null): ?string
    {
        if ($file) {
            $this->delete($oldFilePath);
            return $this->upload($file, $folder, $model);
        }

        return $oldFilePath;
    }

    private function generateFolderFromModel($model): string
    {
        $className = class_basename($model);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $className));
    }
}