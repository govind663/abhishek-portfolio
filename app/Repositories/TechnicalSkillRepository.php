<?php

namespace App\Repositories;

use App\Models\TechnicalSkill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TechnicalSkillRepository
{
    protected TechnicalSkill $model;

    public function __construct(TechnicalSkill $model)
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
        $skill = $this->find($id);

        $data['updated_by'] = Auth::id();

        $skill->update($data);

        return $skill->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE (SOFT DELETE)
    |--------------------------------------------------------------------------
    */
    public function delete($id): bool
    {
        return (bool) $this->find($id)->delete();
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
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME (SOFT DELETE)
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
    | BULK INSERT (SAFE + CLEAN)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $skills, $resumeId): bool
    {
        try {

            if (empty($skills)) {
                return false;
            }

            $now = now();
            $userId = Auth::id();
            $data = [];

            foreach ($skills as $skill) {

                // ✅ skip empty rows
                if (
                    empty($skill['skill_name']) &&
                    empty($skill['category'])
                ) {
                    continue;
                }

                $data[] = [
                    'resume_id'    => $resumeId,
                    'category'     => $skill['category'] ?? null,
                    'skill_name'   => $skill['skill_name'] ?? null,
                    'icon_path'    => $skill['icon_path'] ?? null,
                    'icon_viewbox' => $skill['icon_viewbox'] ?? null,
                    'icon_fill'    => $skill['icon_fill'] ?? null,
                    'status'       => $skill['status'] ?? TechnicalSkill::STATUS_ACTIVE,
                    'created_by'   => $userId,
                    'updated_by'   => $userId,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }

            if (empty($data)) {
                return false;
            }

            $this->model->insert($data);

            Log::info('Skill Bulk Insert Success', [
                'resume_id' => $resumeId,
                'count'     => count($data)
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Bulk Insert Failed', [
                'resume_id' => $resumeId,
                'error'     => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC (PRODUCTION SAFE)
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
            | DELETE REMOVED (SOFT)
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
            | UPSERT
            |--------------------------------------------------------------------------
            */
            foreach ($incoming as $skill) {

                // ✅ skip empty rows
                if (
                    empty($skill['skill_name']) &&
                    empty($skill['category'])
                ) {
                    continue;
                }

                $payload = [
                    'resume_id'    => $resumeId,
                    'category'     => $skill['category'] ?? null,
                    'skill_name'   => $skill['skill_name'] ?? null,
                    'icon_path'    => $skill['icon_path'] ?? null,
                    'icon_viewbox' => $skill['icon_viewbox'] ?? null,
                    'icon_fill'    => $skill['icon_fill'] ?? null,
                    'updated_by'   => $userId,
                ];

                if (!empty($skill['id'])) {

                    // ✅ SAFE UPDATE (ownership protected)
                    $this->model
                        ->where('id', $skill['id'])
                        ->where('resume_id', $resumeId)
                        ->update($payload);

                } else {

                    // ✅ CREATE
                    $payload['created_by'] = $userId;

                    $this->model->create($payload);
                }
            }

            Log::info('Skill Sync Success', [
                'resume_id' => $resumeId,
                'incoming_count' => count($incoming)
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Sync Failed', [
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