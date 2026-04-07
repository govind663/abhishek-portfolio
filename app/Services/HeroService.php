<?php

namespace App\Services;

use App\Models\HeroSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HeroService
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(array $data): HeroSection
    {
        DB::beginTransaction();

        try {
            $hero = new HeroSection();

            // Upload files
            $hero->profile_image = $this->fileService->upload($data['profile_image'] ?? null, null, $hero);
            $hero->background_image = $this->fileService->upload($data['background_image'] ?? null, null, $hero);
            $hero->resume_file = $this->fileService->upload($data['resume_file'] ?? null, null, $hero);

            // Remove file fields
            $data = collect($data)->except([
                'profile_image',
                'background_image',
                'resume_file'
            ])->toArray();

            // Handle typed_items safely
            if (!empty($data['typed_items']) && !is_array($data['typed_items'])) {
                $items = array_map('trim', explode(',', $data['typed_items']));
                $data['typed_items'] = json_encode($items);
            }

            $hero->fill($data);
            $hero->save();

            DB::commit();
            return $hero;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Hero Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update(HeroSection $hero, array $data): HeroSection
    {
        DB::beginTransaction();

        try {
            // Replace only if new file provided
            if (!empty($data['profile_image'])) {
                $hero->profile_image = $this->fileService->replace(
                    $data['profile_image'],
                    $hero->getRawOriginal('profile_image'),
                    null,
                    $hero
                );
            }

            if (!empty($data['background_image'])) {
                $hero->background_image = $this->fileService->replace(
                    $data['background_image'],
                    $hero->getRawOriginal('background_image'),
                    null,
                    $hero
                );
            }

            if (!empty($data['resume_file'])) {
                $hero->resume_file = $this->fileService->replace(
                    $data['resume_file'],
                    $hero->getRawOriginal('resume_file'),
                    null,
                    $hero
                );
            }

            // Remove file fields
            $data = collect($data)->except([
                'profile_image',
                'background_image',
                'resume_file'
            ])->toArray();

            // Handle typed_items safely
            if (!empty($data['typed_items']) && !is_array($data['typed_items'])) {
                $items = array_map('trim', explode(',', $data['typed_items']));
                $data['typed_items'] = json_encode($items);
            }

            $hero->fill($data);
            $hero->save();

            DB::commit();
            return $hero;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Hero Update Failed', [
                'hero_id' => $hero->id,
                'error'   => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete(HeroSection $hero): bool
    {
        DB::beginTransaction();

        try {
            $this->fileService->delete($hero->getRawOriginal('profile_image'));
            $this->fileService->delete($hero->getRawOriginal('background_image'));
            $this->fileService->delete($hero->getRawOriginal('resume_file'));

            $hero->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Hero Delete Failed', [
                'hero_id' => $hero->id,
                'error'   => $e->getMessage()
            ]);

            throw $e;
        }
    }
}