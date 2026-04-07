<?php

namespace App\Services;

use App\Models\Copyright;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CopyrightService
{
    public function store(array $data): Copyright
    {
        DB::beginTransaction();

        try {
            $model = new Copyright();
            $model->fill($data);
            $model->save();

            DB::commit();
            return $model;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Copyright Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update(Copyright $model, array $data): Copyright
    {
        DB::beginTransaction();

        try {
            $model->fill($data);
            $model->save();

            DB::commit();
            return $model;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Copyright Update Failed', [
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }


    public function delete(Copyright $model): bool
    {
        DB::beginTransaction();

        try {
            $model->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Copyright Delete Failed', [
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}