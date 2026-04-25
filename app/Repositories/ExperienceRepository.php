<?php

namespace App\Repositories;

use App\Models\Experience;
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
    | GET ALL (PAGINATED)
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
        return $this->model->with('details')->findOrFail($id);
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
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $experience = $this->find($id);

        DB::transaction(function () use ($experience) {
            $experience->details()->delete();
            $experience->delete();
        });

        return true;
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
            ->get() ?? collect();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME
    |--------------------------------------------------------------------------
    */
    public function deleteByResume($resumeId): bool
    {
        DB::transaction(function () use ($resumeId) {

            $experiences = $this->getByResume($resumeId);

            foreach ($experiences as $experience) {
                $experience->details()->delete();
                $experience->delete();
            }
        });

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (TRANSACTION + LOGGING SAFE)
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

                foreach ($experiences as $exp) {

                    // skip empty rows
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
                        'is_current'  => $exp['is_current'] ?? false,
                        'status'      => $exp['status'] ?? Experience::STATUS_ACTIVE,
                        'created_by'  => $userId,
                        'updated_by'  => $userId,
                    ]);

                    // insert details
                    if (!empty($exp['details'])) {

                        $details = array_map(function ($detail) use ($experience, $userId) {
                            return [
                                'experience_id' => $experience->id,
                                'description'   => $detail['description'] ?? null,
                                'status'        => $detail['status'] ?? Experience::STATUS_ACTIVE,
                                'created_by'    => $userId,
                                'updated_by'    => $userId,
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ];
                        }, $exp['details']);

                        $experience->details()->insert($details);
                    }
                }
            });

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
    | FINAL SYNC (TRANSACTION + SAFE + LOGGING)
    |--------------------------------------------------------------------------
    */
    public function sync(Collection $existing, array $incoming, $resumeId): bool
    {
        try {
            DB::transaction(function () use ($existing, $incoming, $resumeId) {

                $userId = Auth::id();

                $existingIds = $existing->pluck('id')->toArray();
                $incomingIds = collect($incoming)->pluck('id')->filter()->toArray();

                // DELETE removed
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
                        'is_current'  => $exp['is_current'] ?? false,
                        'updated_by'  => $userId,
                    ];

                    if (!empty($exp['id'])) {

                        $experience = $this->model
                            ->where('id', $exp['id'])
                            ->where('resume_id', $resumeId)
                            ->first();

                        if (!$experience) continue;

                        $experience->update($payload);
                        $experience->details()->delete();

                    } else {

                        $payload['created_by'] = $userId;
                        $experience = $this->model->create($payload);
                    }

                    // insert details
                    if (!empty($exp['details'])) {

                        $details = array_map(function ($detail) use ($experience, $userId) {
                            return [
                                'experience_id' => $experience->id,
                                'description'   => $detail['description'] ?? null,
                                'status'        => $detail['status'] ?? Experience::STATUS_ACTIVE,
                                'created_by'    => $userId,
                                'updated_by'    => $userId,
                                'created_at'    => now(),
                                'updated_at'    => now(),
                            ];
                        }, $exp['details']);

                        $experience->details()->insert($details);
                    }
                }
            });

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