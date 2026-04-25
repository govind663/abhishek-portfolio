<?php

namespace App\Repositories;

use App\Models\ExperienceDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExperienceDetailRepository
{
    public function __construct(
        protected ExperienceDetail $model
    ) {}

    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */
    public function all($perPage = 10)
    {
        return $this->model
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find($id): ExperienceDetail
    {
        return $this->model->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(array $data): ExperienceDetail
    {
        try {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            return $this->model->create($data);

        } catch (\Throwable $e) {

            Log::error('ExperienceDetail Create Failed', [
                'payload' => $data,
                'error'   => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update($id, array $data): ExperienceDetail
    {
        try {
            $detail = $this->find($id);

            $data['updated_by'] = Auth::id();

            // ✅ null filtering (important)
            $data = array_filter($data, fn($v) => $v !== null);

            $detail->update($data);

            return $detail->refresh();

        } catch (\Throwable $e) {

            Log::error('ExperienceDetail Update Failed', [
                'id'      => $id,
                'payload' => $data,
                'error'   => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete($id): bool
    {
        try {
            return (bool) $this->find($id)->delete();

        } catch (\Throwable $e) {

            Log::error('ExperienceDetail Delete Failed', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function getByExperience($experienceId): Collection
    {
        return $this->model
            ->where('experience_id', $experienceId)
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY EXPERIENCE (SOFT DELETE SAFE)
    |--------------------------------------------------------------------------
    */
    public function deleteByExperience($experienceId): bool
    {
        try {
            $this->model
                ->where('experience_id', $experienceId)
                ->delete();

            return true;

        } catch (\Throwable $e) {

            Log::error('Delete Details By Experience Failed', [
                'experience_id' => $experienceId,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (SAFE + FILTER + LOGGING)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $details, $experienceId): bool
    {
        try {
            if (empty($details)) {
                Log::warning('Detail Bulk Insert Skipped - Empty', [
                    'experience_id' => $experienceId
                ]);
                return false;
            }

            $now = now();
            $userId = Auth::id();

            $data = [];

            foreach ($details as $detail) {

                if (empty($detail['description'])) continue;

                $data[] = [
                    'experience_id' => $experienceId,
                    'description'   => trim($detail['description']),
                    'status'        => $detail['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                    'created_by'    => $userId,
                    'updated_by'    => $userId,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
            }

            if (empty($data)) {
                Log::warning('Detail Bulk Insert Failed - All Empty', [
                    'experience_id' => $experienceId
                ]);
                return false;
            }

            $this->model->insert($data);

            Log::info('Detail Bulk Insert Success', [
                'experience_id' => $experienceId,
                'count' => count($data)
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('ExperienceDetail Bulk Insert Failed', [
                'experience_id' => $experienceId,
                'payload'       => $details,
                'error'         => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC (FINAL SAFE VERSION)
    |--------------------------------------------------------------------------
    */
    public function sync(Collection $existing, array $newData, $experienceId): bool
    {
        try {
            DB::transaction(function () use ($existing, $newData, $experienceId) {

                $userId = Auth::id();
                $now = now();

                $existingIds = $existing->pluck('id')->toArray();
                $newIds = collect($newData)->pluck('id')->filter()->toArray();

                /*
                |--------------------------------------------------------------------------
                | DELETE REMOVED
                |--------------------------------------------------------------------------
                */
                $deleteIds = array_diff($existingIds, $newIds);

                if (!empty($deleteIds)) {
                    $this->model
                        ->whereIn('id', $deleteIds)
                        ->where('experience_id', $experienceId)
                        ->delete();
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE / CREATE
                |--------------------------------------------------------------------------
                */
                foreach ($newData as $item) {

                    if (empty($item['description'])) continue;

                    if (!empty($item['id'])) {

                        $this->model
                            ->where('id', $item['id'])
                            ->where('experience_id', $experienceId)
                            ->update([
                                'description' => trim($item['description']),
                                'status'      => $item['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                                'updated_by'  => $userId,
                                'updated_at'  => $now,
                            ]);

                    } else {

                        $this->model->create([
                            'experience_id' => $experienceId,
                            'description'   => trim($item['description']),
                            'status'        => $item['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                            'created_by'    => $userId,
                            'updated_by'    => $userId,
                        ]);
                    }
                }
            });

            Log::info('ExperienceDetail Sync Success', [
                'experience_id' => $experienceId,
                'count' => count($newData)
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('ExperienceDetail Sync Failed', [
                'experience_id' => $experienceId,
                'payload'       => $newData,
                'error'         => $e->getMessage()
            ]);

            throw $e;
        }
    }
}