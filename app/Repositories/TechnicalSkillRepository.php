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
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find(int $id): TechnicalSkill
    {
        try {

            return $this->model->findOrFail($id);

        } catch (\Throwable $e) {

            Log::error('Technical Skill Find Failed', [
                'skill_id' => $id,
                'message'  => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY RESUME
    |--------------------------------------------------------------------------
    */
    public function getByResume(int $resumeId): Collection
    {
        try {

            return $this->model
                ->where('resume_id', $resumeId)
                ->latest('id')
                ->get();

        } catch (\Throwable $e) {

            Log::error('Technical Skill Fetch Failed', [
                'resume_id' => $resumeId,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE SINGLE
    |--------------------------------------------------------------------------
    */
    public function create(array $data): TechnicalSkill
    {
        try {

            $userId = Auth::id();

            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $skill = $this->model->create($data);

            Log::info('Technical Skill Created', [
                'skill_id'  => $skill->id,
                'resume_id' => $skill->resume_id,
                'created_by'=> $userId,
            ]);

            return $skill;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Create Failed', [
                'resume_id' => $data['resume_id'] ?? null,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BULK CREATE
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(
        array $skills,
        int $resumeId
    ): bool {

        try {

            $userId = Auth::id();

            $rows = [];

            foreach ($skills as $skill) {

                /*
                |--------------------------------------------------------------------------
                | Skip Empty Row
                |--------------------------------------------------------------------------
                */
                if (
                    empty($skill['skill_name']) &&
                    empty($skill['category'])
                ) {
                    continue;
                }

                $rows[] = [

                    'resume_id'    => $resumeId,

                    'skill_name'   => $skill['skill_name'] ?? null,

                    'category'     => $skill['category'] ?? null,

                    'icon_path'    => $skill['icon_path'] ?? null,

                    'icon_viewbox' => $skill['icon_viewbox'] ?? '0 0 24 24',

                    'icon_fill'    => $skill['icon_fill'] ?? '#000',

                    'status'       => TechnicalSkill::STATUS_ACTIVE,

                    'created_by'   => $userId,

                    'updated_by'   => $userId,

                    'created_at'   => now(),

                    'updated_at'   => now(),

                ];

            }

            if (empty($rows)) {

                Log::warning('Technical Skill Bulk Insert Skipped', [
                    'resume_id' => $resumeId,
                ]);

                return false;
            }

            $this->model->insert($rows);

            Log::info('Technical Skills Bulk Created', [
                'resume_id' => $resumeId,
                'count'     => count($rows),
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Bulk Insert Failed', [
                'resume_id' => $resumeId,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile(),
            ]);

            throw $e;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE SINGLE
    |--------------------------------------------------------------------------
    */
    public function update(
        int $id,
        array $data
    ): TechnicalSkill {

        try {

            $skill = $this->find($id);

            $data['updated_by'] = Auth::id();

            $skill->update(array_filter(
                $data,
                fn ($value) => !is_null($value)
            ));

            Log::info('Technical Skill Updated', [
                'skill_id'  => $skill->id,
                'resume_id' => $skill->resume_id,
            ]);

            return $skill->refresh();

        } catch (\Throwable $e) {

            Log::error('Technical Skill Update Failed', [
                'skill_id' => $id,
                'message'  => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
            ]);

            throw $e;
        }

    }

    /*
    |--------------------------------------------------------------------------
    | SYNC UPDATE
    |--------------------------------------------------------------------------
    */
    public function sync(
        Collection $existing,
        array $incoming,
        int $resumeId
    ): bool {

        try {

            $userId = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | DELETE REMOVED SKILLS
            |--------------------------------------------------------------------------
            */
            $oldIds = $existing
                ->pluck('id')
                ->toArray();

            $newIds = collect($incoming)
                ->pluck('id')
                ->filter()
                ->toArray();

            $deleteIds = array_diff(
                $oldIds,
                $newIds
            );

            if (!empty($deleteIds)) {

                $this->model
                    ->whereIn('id', $deleteIds)
                    ->where('resume_id', $resumeId)
                    ->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE / UPDATE
            |--------------------------------------------------------------------------
            */
            foreach ($incoming as $skill) {

                /*
                |--------------------------------------------------------------------------
                | Skip Empty Row
                |--------------------------------------------------------------------------
                */
                if (
                    empty($skill['skill_name']) &&
                    empty($skill['category'])
                ) {
                    continue;
                }

                $payload = [

                    'skill_name'   => $skill['skill_name'] ?? null,

                    'category'     => $skill['category'] ?? null,

                    'icon_path'    => $skill['icon_path'] ?? null,

                    'icon_viewbox' => $skill['icon_viewbox'] ?? '0 0 24 24',

                    'icon_fill'    => $skill['icon_fill'] ?? '#000',

                    'updated_by'   => $userId,

                ];

                /*
                |--------------------------------------------------------------------------
                | Update Existing Skill
                |--------------------------------------------------------------------------
                */
                if (!empty($skill['id'])) {

                    $this->model
                        ->where('id', $skill['id'])
                        ->where('resume_id', $resumeId)
                        ->update($payload);

                }

                /*
                |--------------------------------------------------------------------------
                | Create New Skill
                |--------------------------------------------------------------------------
                */
                else {

                    $payload['resume_id'] = $resumeId;

                    $payload['created_by'] = $userId;

                    $this->model->create($payload);

                }

            }

            Log::info('Technical Skill Sync Completed', [

                'resume_id' => $resumeId,

                'total'     => count($incoming),

                'deleted'   => count($deleteIds),

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Sync Failed', [

                'resume_id' => $resumeId,

                'message'   => $e->getMessage(),

                'line'      => $e->getLine(),

                'file'      => $e->getFile(),

            ]);

            throw $e;

        }

    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME
    |--------------------------------------------------------------------------
    */
    public function deleteByResume(int $resumeId): bool
    {
        try {

            $count = $this->model
                ->where('resume_id', $resumeId)
                ->delete();

            Log::info('Technical Skills Deleted By Resume', [
                'resume_id' => $resumeId,
                'count'     => $count,
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Delete By Resume Failed', [
                'resume_id' => $resumeId,
                'message'   => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete(int $id): bool
    {
        try {

            $skill = $this->find($id);

            $skill->delete();

            Log::info('Technical Skill Deleted', [
                'skill_id'  => $id,
                'resume_id' => $skill->resume_id,
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Technical Skill Delete Failed', [
                'skill_id' => $id,
                'message'  => $e->getMessage(),
                'line'     => $e->getLine(),
                'file'     => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE LIST
    |--------------------------------------------------------------------------
    */
    public function active(int $perPage = 10)
    {
        try {

            return $this->model
                ->active()
                ->latest('id')
                ->paginate($perPage);

        } catch (\Throwable $e) {

            Log::error('Technical Skill Active List Failed', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            throw $e;
        }
    }

}