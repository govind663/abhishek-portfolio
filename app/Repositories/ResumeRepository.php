<?php

namespace App\Repositories;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;

class ResumeRepository
{
    protected Resume $model;

    public function __construct(Resume $model)
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
            ->where('created_by', Auth::id()) // 🔐 user scope
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find($id): Resume
    {
        return $this->model
            ->where('id', $id)
            ->where('created_by', Auth::id()) // 🔐 secure
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 FIND WITH RELATIONS (SAFE)
    |--------------------------------------------------------------------------
    */
    public function findWithRelations($id): Resume
    {
        return $this->model
            ->with([
                'educations',
                'skills',
                'experiences.details'
            ])
            ->where('created_by', Auth::id()) // 🔐 secure
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 FIND BY ID + USER (OPTIONAL SECURITY)
    |--------------------------------------------------------------------------
    */
    public function findByUser($id, $userId): Resume
    {
        return $this->model
            ->where('id', $id)
            ->where('created_by', $userId)
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | GET ACTIVE (PAGINATED)
    |--------------------------------------------------------------------------
    */
    public function active($perPage = 10)
    {
        return $this->model
            ->active()
            ->where('created_by', Auth::id()) // 🔐 secure
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE (WITH AUDIT)
    |--------------------------------------------------------------------------
    */
    public function create(array $data): Resume
    {
        $data['created_by'] = $data['created_by'] ?? Auth::id();
        $data['updated_by'] = Auth::id();

        return $this->model->create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 UPDATE (FINAL SAFE VERSION)
    |--------------------------------------------------------------------------
    */
    public function update($resume, array $data): Resume
    {
        if (!$resume instanceof Resume) {
            $resume = $this->find($resume); // 🔐 secure find
        }

        $data['updated_by'] = Auth::id();

        $resume->update($data);

        return $resume->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete(Resume $resume): bool
    {
        return $resume->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 DELETE WITH RELATIONS (SAFE CASCADE)
    |--------------------------------------------------------------------------
    */
    public function deleteWithRelations(Resume $resume): bool
    {
        $resume->load([
            'educations',
            'skills',
            'experiences.details'
        ]);

        $resume->educations()->delete();
        $resume->skills()->delete();

        foreach ($resume->experiences as $experience) {
            $experience->details()->delete();
            $experience->delete();
        }

        return $resume->delete();
    }
}