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
    public function find(int $id): ExperienceDetail
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

            return DB::transaction(function () use ($data) {

                $userId = Auth::id();

                $detail = $this->model->create([

                    'experience_id' => $data['experience_id'],

                    'description' => trim($data['description']),

                    'status' => $data['status'] ?? ExperienceDetail::STATUS_ACTIVE,

                    'created_by' => $userId,

                    'updated_by' => $userId,

                ]);

                Log::info('Experience Detail Created', [

                    'experience_detail_id' => $detail->id,

                    'experience_id' => $detail->experience_id,

                ]);

                return $detail;

            });

        } catch (\Throwable $e) {

            Log::error('Experience Detail Create Failed', [

                'experience_id' => $data['experience_id'] ?? null,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(
        int $id,
        array $data
    ): ExperienceDetail {

        try {

            return DB::transaction(function () use ($id, $data) {

                $detail = $this->find($id);

                $detail->update(array_filter([

                    'description' => isset($data['description'])
                        ? trim($data['description'])
                        : $detail->description,

                    'status' => $data['status'] ?? $detail->status,

                    'updated_by' => Auth::id(),

                ], fn ($value) => !is_null($value)));

                Log::info('Experience Detail Updated', [

                    'experience_detail_id' => $detail->id,

                    'experience_id' => $detail->experience_id,

                ]);

                return $detail->fresh();

            });

        } catch (\Throwable $e) {

            Log::error('Experience Detail Update Failed', [

                'experience_detail_id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete(int $id): bool
    {
        try {

            return DB::transaction(function () use ($id) {

                $detail = $this->find($id);

                $detail->delete();

                Log::info('Experience Detail Deleted', [

                    'experience_detail_id' => $id,

                ]);

                return true;

            });

        } catch (\Throwable $e) {

            Log::error('Experience Detail Delete Failed', [

                'experience_detail_id' => $id,

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;

        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function getByExperience(
        int $experienceId
    ): Collection {

        return $this->model
            ->where('experience_id', $experienceId)
            ->latest('id')
            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function deleteByExperience(
        int $experienceId
    ): bool {

        try {

            $count = $this->model
                ->where('experience_id', $experienceId)
                ->delete();

            Log::info('Experience Details Deleted', [

                'experience_id' => $experienceId,

                'count' => $count,

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Detail Delete Failed', [

                'experience_id' => $experienceId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (SAFE + FILTER + LOGGING)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(
        array $details,
        int $experienceId
    ): bool {

        try {

            if (empty($details)) {
                return false;
            }

            $now = now();

            $userId = Auth::id();

            $rows = [];

            foreach ($details as $detail) {

                $description = trim($detail['description'] ?? '');

                if ($description === '') {
                    continue;
                }

                $exists = $this->model
                    ->where('experience_id', $experienceId)
                    ->where('description', $description)
                    ->exists();

                if ($exists) {
                    continue;
                }

                $rows[] = [

                    'experience_id' => $experienceId,

                    'description' => $description,

                    'status' => $detail['status'] ?? ExperienceDetail::STATUS_ACTIVE,

                    'created_by' => $userId,

                    'updated_by' => $userId,

                    'created_at' => $now,

                    'updated_at' => $now,

                ];

            }

            if (empty($rows)) {
                return false;
            }

            $this->model->insert($rows);

            Log::info('Experience Detail Bulk Insert', [

                'experience_id' => $experienceId,

                'count' => count($rows),

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Detail Bulk Insert Failed', [

                'experience_id' => $experienceId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | SYNC (FINAL SAFE VERSION)
    |--------------------------------------------------------------------------
    */
    public function sync(
        Collection $existing,
        array $incoming,
        int $experienceId
    ): bool {

        try {

            DB::transaction(function () use (
                $existing,
                $incoming,
                $experienceId
            ) {

                $userId = Auth::id();

                $now = now();

                $existingIds = $existing
                    ->pluck('id')
                    ->toArray();

                $incomingIds = collect($incoming)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $deleteIds = array_diff(
                    $existingIds,
                    $incomingIds
                );

                if (!empty($deleteIds)) {

                    $this->model
                        ->whereIn('id', $deleteIds)
                        ->delete();

                }

                foreach ($incoming as $detail) {

                    $description = trim(
                        $detail['description'] ?? ''
                    );

                    if ($description === '') {
                        continue;
                    }

                    if (!empty($detail['id'])) {

                        $this->model
                            ->where('id', $detail['id'])
                            ->where('experience_id', $experienceId)
                            ->update([

                                'description' => $description,

                                'status' => $detail['status']
                                    ?? ExperienceDetail::STATUS_ACTIVE,

                                'updated_by' => $userId,

                                'updated_at' => $now,

                            ]);

                    } else {

                        $duplicate = $this->model
                            ->where('experience_id', $experienceId)
                            ->where('description', $description)
                            ->exists();

                        if ($duplicate) {
                            continue;
                        }

                        $this->model->create([

                            'experience_id' => $experienceId,

                            'description' => $description,

                            'status' => $detail['status']
                                ?? ExperienceDetail::STATUS_ACTIVE,

                            'created_by' => $userId,

                            'updated_by' => $userId,

                        ]);

                    }

                }

            });

            Log::info('Experience Detail Sync Success', [

                'experience_id' => $experienceId,

                'count' => count($incoming),

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Detail Sync Failed', [

                'experience_id' => $experienceId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;

        }

    }
}