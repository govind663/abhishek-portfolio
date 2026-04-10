<?php

namespace App\Repositories;

use App\Models\Education;
use Illuminate\Support\Facades\Auth;

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
    public function bulkInsert(array $educations, $resumeId)
    {
        if (empty($educations)) {
            return false;
        }

        $now = now();
        $userId = Auth::id();

        $data = array_map(function ($edu) use ($resumeId, $now, $userId) {
            return [
                'resume_id'   => $resumeId,
                'degree'      => $edu['degree'] ?? null,
                'field'       => $edu['field'] ?? null,
                'institution' => $edu['institution'] ?? null,
                'university'  => $edu['university'] ?? null,
                'location'    => $edu['location'] ?? null,
                'start_date'  => $edu['start_date'] ?? null,
                'end_date'    => $edu['end_date'] ?? null,
                'status'      => $edu['status'] ?? 'active',

                'created_by'  => $userId,
                'updated_by'  => $userId,

                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }, $educations);

        return $this->model->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔥 SMART SYNC (MAIN UPGRADE)
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
        foreach ($incoming as $edu) {

            $payload = [
                'resume_id'   => $resumeId,
                'degree'      => $edu['degree'],
                'field'       => $edu['field'] ?? null,
                'institution' => $edu['institution'],
                'university'  => $edu['university'] ?? null,
                'location'    => $edu['location'] ?? null,
                'start_date'  => $edu['start_date'],
                'end_date'    => $edu['end_date'] ?? null,
                'updated_by'  => $userId,
            ];

            if (!empty($edu['id'])) {

                // UPDATE
                $this->model
                    ->where('id', $edu['id'])
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