<?php

namespace App\Repositories;

use App\Models\ExperienceDetail;
use Illuminate\Support\Facades\Auth;

class ExperienceDetailRepository
{
    protected ExperienceDetail $model;

    public function __construct(ExperienceDetail $model)
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
        $detail = $this->find($id);

        $data['updated_by'] = Auth::id();

        $detail->update($data);

        return $detail;
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
    | GET BY EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function getByExperience($experienceId)
    {
        return $this->model
            ->where('experience_id', $experienceId)
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY EXPERIENCE (FIXED)
    |--------------------------------------------------------------------------
    */
    public function deleteByExperience($experienceId)
    {
        return $this->model
            ->where('experience_id', $experienceId)
            ->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (OPTIMIZED)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $details, $experienceId)
    {
        if (empty($details)) {
            return false;
        }

        $now = now();
        $userId = Auth::id();

        $data = array_map(function ($detail) use ($experienceId, $now, $userId) {
            return [
                'experience_id' => $experienceId,
                'description'   => $detail['description'] ?? null,
                'status'        => $detail['status'] ?? ExperienceDetail::STATUS_ACTIVE,

                'created_by'    => $userId,
                'updated_by'    => $userId,

                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }, $details);

        return $this->model->insert($data);
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