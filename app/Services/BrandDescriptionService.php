<?php

namespace App\Services;

use App\Models\BrandDescription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BrandDescriptionService
{
    protected FileUploadService $fileService;

    public function __construct(FileUploadService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(array $data): BrandDescription
    {
        DB::beginTransaction();

        try {
            $model = new BrandDescription();

            // Upload logo
            $model->logo = $this->fileService->upload($data['logo'] ?? null, null, $model);

            $data = collect($data)->except(['logo'])->toArray();

            $model->fill($data);
            $model->save();

            DB::commit();
            return $model;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('BrandDescription Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update(BrandDescription $model, array $data): BrandDescription
    {
        DB::beginTransaction();

        try {
            if (!empty($data['logo'])) {
                $model->logo = $this->fileService->replace(
                    $data['logo'],
                    $model->getRawOriginal('logo'),
                    null,
                    $model
                );
            }

            $data = collect($data)->except(['logo'])->toArray();

            $model->fill($data);
            $model->save();

            DB::commit();
            return $model;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('BrandDescription Update Failed', [
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete(BrandDescription $model): bool
    {
        DB::beginTransaction();

        try {
            $this->fileService->delete($model->getRawOriginal('logo'));

            $model->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('BrandDescription Delete Failed', [
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}