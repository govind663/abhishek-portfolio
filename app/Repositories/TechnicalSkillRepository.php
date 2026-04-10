<?php

namespace App\Repositories;

use App\Models\TechnicalSkill;
use Illuminate\Support\Facades\Auth;

class TechnicalSkillRepository
{
    protected TechnicalSkill $model;

    public function __construct(TechnicalSkill $model)
    {
        $this->model = $model;
    }

    /*
    |--------------------------------------------------------------------------
    | GET ALL
    |--------------------------------------------------------------------------
    */
    public function all()
    {
        return $this->model->latest('id')->get();
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
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        return $this->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY RESUME
    |--------------------------------------------------------------------------
    */
    public function getByResume($resumeId)
    {
        return $this->model
            ->where('resume_id', $resumeId)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME
    |--------------------------------------------------------------------------
    */
    public function deleteByResume($resumeId)
    {
        return $this->model
            ->where('resume_id', $resumeId)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $skills, $resumeId)
    {
        if (empty($skills)) {
            return false;
        }

        $now = now();
        $userId = Auth::id();

        $data = array_map(function ($skill) use ($resumeId, $now, $userId) {
            return [
                'resume_id'    => $resumeId,
                'category'     => $skill['category'] ?? null,
                'skill_name'   => $skill['skill_name'] ?? null,
                'icon_path'    => $skill['icon_path'] ?? null,
                'icon_viewbox' => $skill['icon_viewbox'] ?? null,
                'icon_fill'    => $skill['icon_fill'] ?? null,
                'status'       => $skill['status'] ?? 'active',

                'created_by'   => $userId,
                'updated_by'   => $userId,

                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }, $skills);

        return $this->model->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 SMART SYNC
    |--------------------------------------------------------------------------
    */
    public function sync($existing, $incoming, $resumeId)
    {
        $userId = Auth::id();

        $existingIds = collect($existing)->pluck('id')->toArray();
        $incomingIds = collect($incoming)->pluck('id')->filter()->toArray();

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED
        |--------------------------------------------------------------------------
        */
        $deleteIds = array_diff($existingIds, $incomingIds);

        if (!empty($deleteIds)) {
            $this->model->whereIn('id', $deleteIds)->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE / CREATE
        |--------------------------------------------------------------------------
        */
        foreach ($incoming as $skill) {

            $payload = [
                'resume_id'    => $resumeId,
                'category'     => $skill['category'],
                'skill_name'   => $skill['skill_name'],
                'icon_path'    => $skill['icon_path'],
                'icon_viewbox' => $skill['icon_viewbox'] ?? null,
                'icon_fill'    => $skill['icon_fill'] ?? null,
                'updated_by'   => $userId,
            ];

            if (!empty($skill['id'])) {

                // UPDATE
                $this->model
                    ->where('id', $skill['id'])
                    ->update($payload);

            } else {

                // CREATE
                $payload['created_by'] = $userId;

                $this->model->create($payload);
            }
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE LIST
    |--------------------------------------------------------------------------
    */
    public function active()
    {
        return $this->model
            ->active()
            ->latest('id')
            ->get();
    }
}