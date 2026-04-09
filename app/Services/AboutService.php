<?php

namespace App\Services;

use App\Models\AboutSection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AboutService
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            $about = new AboutSection();

            // ✅ Upload profile image
            $about->profile_image = $this->fileService->upload(
                $data['profile_image'] ?? null,
                null,
                $about
            );

            // ✅ Remove file field
            $data = collect($data)->except(['profile_image'])->toArray();

            $about->fill($data);
            $about->save();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('AboutService Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update($model, array $data)
    {
        DB::beginTransaction();

        try {

            // ✅ Replace image only if new uploaded
            if (!empty($data['profile_image'])) {
                $model->profile_image = $this->fileService->replace(
                    $data['profile_image'],
                    $model->getRawOriginal('profile_image'),
                    null,
                    $model
                );
            }

            // ✅ Remove file field
            $data = collect($data)->except(['profile_image'])->toArray();

            $model->fill($data);
            $model->save();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('AboutService Update Failed', [
                'id'    => $model->id ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete($model)
    {
        DB::beginTransaction();

        try {

            // ✅ Delete image
            $this->fileService->delete(
                $model->getRawOriginal('profile_image')
            );

            $model->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('AboutService Delete Failed', [
                'id'    => $model->id ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}