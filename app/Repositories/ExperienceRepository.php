<?php

namespace App\Repositories;

use App\Models\Experience;
use App\Models\ExperienceDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExperienceRepository
{
    protected Experience $model;

    public function __construct(Experience $model)
    {
        $this->model = $model;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */
    public function all($perPage = 10)
    {
        return $this->model
            ->with('details')
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find($id)
    {
        return $this->model
            ->with('details')
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY RESUME
    |--------------------------------------------------------------------------
    */
    public function findByResume($id, $resumeId)
    {
        return $this->model
            ->where('id', $id)
            ->where('resume_id', $resumeId)
            ->with('details')
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(array $data)
    {
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return $this->model->create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update($id, array $data)
    {
        $experience = $this->find($id);

        $data['updated_by'] = Auth::id();

        $experience->update($data);

        return $experience->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE (SOFT DELETE SAFE)
    |--------------------------------------------------------------------------
    */
    public function delete($id): bool
    {
        return DB::transaction(function () use ($id) {

            $experience = $this->find($id);

            // ✅ child first
            $experience->details()->delete();

            return $experience->delete();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY RESUME
    |--------------------------------------------------------------------------
    */
    public function getByResume($resumeId): Collection
    {
        return $this->model
            ->where('resume_id', $resumeId)
            ->with('details')
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME (SOFT DELETE SAFE)
    |--------------------------------------------------------------------------
    */
    public function deleteByResume($resumeId): bool
    {
        return DB::transaction(function () use ($resumeId) {

            $experiences = $this->getByResume($resumeId);

            foreach ($experiences as $experience) {
                $experience->details()->delete();
                $experience->delete();
            }

            return true;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (SAFE + FILTERED)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $experiences, $resumeId): bool
    {
        try {

            if (empty($experiences)) {
                return false;
            }

            DB::transaction(function () use ($experiences, $resumeId) {

                $userId = Auth::id();
                $now = now();

                foreach ($experiences as $exp) {

                    // ✅ skip empty
                    if (empty($exp['designation']) && empty($exp['company'])) {
                        continue;
                    }

                    $experience = $this->model->create([
                        'resume_id'   => $resumeId,
                        'designation' => $exp['designation'] ?? null,
                        'company'     => $exp['company'] ?? null,
                        'location'    => $exp['location'] ?? null,
                        'start_date'  => $exp['start_date'] ?? null,
                        'end_date'    => $exp['end_date'] ?? null,
                        'is_current'  => !empty($exp['is_current']),
                        'status'      => $exp['status'] ?? Experience::STATUS_ACTIVE,
                        'created_by'  => $userId,
                        'updated_by'  => $userId,
                        'created_at'  => $now,
                        'updated_at'  => $now,
                    ]);

                    // ✅ details insert
                    $this->insertDetails($experience->id, $exp['details'] ?? [], $userId, $now);
                }
            });

            Log::info('Experience Bulk Insert Success', [
                'resume_id' => $resumeId
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Bulk Insert Failed', [
                'resume_id' => $resumeId,
                'error'     => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC (FINAL PRODUCTION SAFE)
    |--------------------------------------------------------------------------
    */
    public function sync(Collection $existing, array $incoming, $resumeId): bool
    {
        try {

            DB::transaction(function () use ($existing, $incoming, $resumeId) {

                $userId = Auth::id();
                $now = now();

                $existingIds = $existing->pluck('id')->toArray();
                $incomingIds = collect($incoming)->pluck('id')->filter()->toArray();

                /*
                |--------------------------------------------------------------------------
                | DELETE REMOVED
                |--------------------------------------------------------------------------
                */
                $deleteIds = array_diff($existingIds, $incomingIds);

                if (!empty($deleteIds)) {
                    $this->model
                        ->whereIn('id', $deleteIds)
                        ->where('resume_id', $resumeId)
                        ->get()
                        ->each(function ($exp) {
                            $exp->details()->delete();
                            $exp->delete();
                        });
                }

                /*
                |--------------------------------------------------------------------------
                | UPSERT
                |--------------------------------------------------------------------------
                */
                foreach ($incoming as $exp) {

                    if (empty($exp['designation']) && empty($exp['company'])) {
                        continue;
                    }

                    $payload = [
                        'resume_id'   => $resumeId,
                        'designation' => $exp['designation'] ?? null,
                        'company'     => $exp['company'] ?? null,
                        'location'    => $exp['location'] ?? null,
                        'start_date'  => $exp['start_date'] ?? null,
                        'end_date'    => $exp['end_date'] ?? null,
                        'is_current'  => !empty($exp['is_current']),
                        'updated_by'  => $userId,
                        'updated_at'  => $now,
                    ];

                    if (!empty($exp['id'])) {

                        $experience = $this->model
                            ->where('id', $exp['id'])
                            ->where('resume_id', $resumeId)
                            ->first();

                        if (!$experience) continue;

                        $experience->update($payload);

                        // ⚠️ IMPORTANT FIX: details reset
                        $experience->details()->delete();

                    } else {

                        $payload['created_by'] = $userId;
                        $payload['created_at'] = $now;

                        $experience = $this->model->create($payload);
                    }

                    // ✅ insert fresh details
                    $this->insertDetails($experience->id, $exp['details'] ?? [], $userId, $now);
                }
            });

            Log::info('Experience Sync Success', [
                'resume_id' => $resumeId
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Sync Failed', [
                'resume_id' => $resumeId,
                'error'     => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT DETAILS (REUSABLE 🔥)
    |--------------------------------------------------------------------------
    */
    private function insertDetails($experienceId, array $details, $userId, $now): void
    {
        if (empty($details)) return;

        $data = [];

        foreach ($details as $detail) {

            if (empty($detail['description'])) continue;

            $data[] = [
                'experience_id' => $experienceId,
                'description'   => $detail['description'],
                'status'        => $detail['status'] ?? Experience::STATUS_ACTIVE,
                'created_by'    => $userId,
                'updated_by'    => $userId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        if (!empty($data)) {
            ExperienceDetail::insert($data);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE LIST
    |--------------------------------------------------------------------------
    */
    public function active($perPage = 10)
    {
        return $this->model
            ->with('details')
            ->active()
            ->latest('id')
            ->paginate($perPage);
    }
}