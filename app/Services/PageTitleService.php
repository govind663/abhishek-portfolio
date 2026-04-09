<?php

namespace App\Services;

use App\Models\PageTitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PageTitleService
{
    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            PageTitle::create($data);

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('PageTitleService Store Failed', [
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

            Log::error('PageTitleService Update Failed', [
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

            Log::error('PageTitleService Delete Failed', [
                'id'    => $model->id ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}