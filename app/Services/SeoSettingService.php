<?php

namespace App\Services;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeoSettingService
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(array $data): SeoSetting
    {
        DB::beginTransaction();

        try {
            $seo = new SeoSetting();

            // Upload files
            $seo->og_image = $this->fileService->upload($data['og_image'] ?? null, null, $seo);
            $seo->twitter_image = $this->fileService->upload($data['twitter_image'] ?? null, null, $seo);

            // Remove file fields
            $data = collect($data)->except(['og_image', 'twitter_image'])->toArray();

            $seo->fill($data);
            $seo->save();

            DB::commit();
            return $seo;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('SEO Store Failed', [
                'error' => $e->getMessage(),
                'data'  => $data
            ]);

            throw $e;
        }
    }

    public function update(SeoSetting $seo, array $data): SeoSetting
    {
        DB::beginTransaction();

        try {
            // Replace only if new file provided
            if (!empty($data['og_image'])) {
                $seo->og_image = $this->fileService->replace(
                    $data['og_image'],
                    $seo->getRawOriginal('og_image'),
                    null,
                    $seo
                );
            }

            if (!empty($data['twitter_image'])) {
                $seo->twitter_image = $this->fileService->replace(
                    $data['twitter_image'],
                    $seo->getRawOriginal('twitter_image'),
                    null,
                    $seo
                );
            }

            // Remove file fields
            $data = collect($data)->except(['og_image', 'twitter_image'])->toArray();

            $seo->fill($data);
            $seo->save();

            DB::commit();
            return $seo;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('SEO Update Failed', [
                'seo_id' => $seo->id,
                'error'  => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete(SeoSetting $seo): bool
    {
        DB::beginTransaction();

        try {
            $this->fileService->delete($seo->getRawOriginal('og_image'));
            $this->fileService->delete($seo->getRawOriginal('twitter_image'));

            $seo->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('SEO Delete Failed', [
                'seo_id' => $seo->id,
                'error'  => $e->getMessage()
            ]);

            throw $e;
        }
    }
}