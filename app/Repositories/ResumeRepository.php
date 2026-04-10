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
    | FIND WITH RELATIONS
    |--------------------------------------------------------------------------
    */
    public function findWithRelations($id)
    {
        return $this->model->with([
            'educations',
            'skills',
            'experiences.details'
        ])->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE (WITH AUDIT)
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
    | UPDATE (WITH AUDIT)
    |--------------------------------------------------------------------------
    */
    public function update(Resume $resume, array $data)
    {
        $data['updated_by'] = Auth::id();

        $resume->update($data);

        return $resume->refresh(); // 🔥 latest data return
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete(Resume $resume)
    {
        return $resume->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE WITH RELATIONS (SAFE CASCADE)
    |--------------------------------------------------------------------------
    */
    public function deleteWithRelations(Resume $resume)
    {
        // eager load relations
        $resume->load([
            'educations',
            'skills',
            'experiences.details'
        ]);

        // delete child relations
        $resume->educations()->delete();
        $resume->skills()->delete();

        foreach ($resume->experiences as $experience) {
            $experience->details()->delete();
            $experience->delete();
        }

        return $resume->delete();
    }
}