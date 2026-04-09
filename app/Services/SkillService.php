<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SkillService
{
    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            // ✅ Ensure percentage is integer
            if (isset($data['percentage'])) {
                $data['percentage'] = (int) $data['percentage']; 
            }

            Skill::create($data);

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('SkillService Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update($model, array $data)
    {
        DB::beginTransaction();

        try {

            // ✅ Ensure percentage is integer
            if (isset($data['percentage'])) {
                $data['percentage'] = (int) $data['percentage'];
            }

            $model->update($data);

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('SkillService Update Failed', [
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

            Log::error('SkillService Delete Failed', [
                'id'    => $model->id ?? null,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}