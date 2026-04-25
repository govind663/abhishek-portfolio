<?php

namespace App\Repositories;

use App\Models\Education;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EducationRepository
{
    protected Education $model;

    public function __construct(Education $model)
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
        return $this->model->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY RESUME (SECURE)
    |--------------------------------------------------------------------------
    */
    public function findByResume($id, $resumeId)
    {
        return $this->model
            ->where('id', $id)
            ->where('resume_id', $resumeId)
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
        $education = $this->find($id);

        $data['updated_by'] = Auth::id();

        $education->update($data);

        return $education->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete($id): bool
    {
        return (bool) $this->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY RESUME (SAFE)
    |--------------------------------------------------------------------------
    */
    public function getByResume($resumeId): Collection
    {
        return $this->model
            ->where('resume_id', $resumeId)
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
        $this->model
            ->where('resume_id', $resumeId)
            ->delete();

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (SAFE + CLEAN + VALIDATION)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $educations, $resumeId): bool
    {
        try {
            if (empty($educations)) {
                return false;
            }

            $now = now();
            $userId = Auth::id();

            $data = [];

            foreach ($educations as $edu) {

                // 🔥 skip empty rows (important for dynamic forms)
                if (empty($edu['degree']) && empty($edu['institution'])) {
                    continue;
                }

                $data[] = [
                    'resume_id'   => $resumeId,
                    'degree'      => $edu['degree'] ?? null,
                    'field'       => $edu['field'] ?? null,
                    'institution' => $edu['institution'] ?? null,
                    'university'  => $edu['university'] ?? null,
                    'location'    => $edu['location'] ?? null,
                    'start_date'  => $edu['start_date'] ?? null,
                    'end_date'    => $edu['end_date'] ?? null,
                    'status'      => $edu['status'] ?? Education::STATUS_ACTIVE,

                    'created_by'  => $userId,
                    'updated_by'  => $userId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            if (empty($data)) {
                return false;
            }

            return $this->model->insert($data);

        } catch (\Throwable $e) {

            Log::error('Education Bulk Insert Failed', [
                'resume_id' => $resumeId,
                'error'     => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC (FINAL SAFE + LOGGING VERSION)
    |--------------------------------------------------------------------------
    */
    public function sync(Collection $existing, array $incoming, $resumeId): bool
    {
        try {
            $userId = Auth::id();

            $existingIds = $existing->pluck('id')->toArray();
            $incomingIds = collect($incoming)->pluck('id')->filter()->toArray();

            /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED RECORDS
            |--------------------------------------------------------------------------
            */
            $deleteIds = array_diff($existingIds, $incomingIds);

            if (!empty($deleteIds)) {
                $this->model
                    ->whereIn('id', $deleteIds)
                    ->where('resume_id', $resumeId)
                    ->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE / CREATE
            |--------------------------------------------------------------------------
            */
            foreach ($incoming as $edu) {

                if (empty($edu['degree']) && empty($edu['institution'])) {
                    continue;
                }

                $payload = [
                    'resume_id'   => $resumeId,
                    'degree'      => $edu['degree'] ?? null,
                    'field'       => $edu['field'] ?? null,
                    'institution' => $edu['institution'] ?? null,
                    'university'  => $edu['university'] ?? null,
                    'location'    => $edu['location'] ?? null,
                    'start_date'  => $edu['start_date'] ?? null,
                    'end_date'    => $edu['end_date'] ?? null,
                    'updated_by'  => $userId,
                ];

                if (!empty($edu['id'])) {

                    $this->model
                        ->where('id', $edu['id'])
                        ->where('resume_id', $resumeId)
                        ->update($payload);

                } else {

                    $payload['created_by'] = $userId;

                    $this->model->create($payload);
                }
            }

            return true;

        } catch (\Throwable $e) {

            Log::error('Education Sync Failed', [
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
            ->active()
            ->latest('id')
            ->paginate($perPage);
    }
}