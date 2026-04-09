<?php

namespace App\Services;

use App\Models\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatService
{
    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            Stat::create($data);

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('StatService Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update($model, array $data)
    {
        DB::beginTransaction();

        try {

            $model->update($data);

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('StatService Update Failed', [
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

            $model->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('StatService Delete Failed', [
                'id'    => $model->id ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}