<?php

namespace App\Repositories;

use App\Models\Experience;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $experience = $this->find($id);

        $data['updated_by'] = Auth::id();

        $experience->update($data);

        return $experience;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE
    |--------------------------------------------------------------------------
    */
    public function delete($id)
    {
        $experience = $this->find($id);

        // ⚠️ IMPORTANT: delete details first
        $experience->details()->delete();

        return $experience->delete();
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
            ->with('details')
            ->latest('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY RESUME (FIXED)
    |--------------------------------------------------------------------------
    */
    public function deleteByResume($resumeId)
    {
        $experiences = $this->model
            ->where('resume_id', $resumeId)
            ->get();

        foreach ($experiences as $experience) {
            $experience->details()->delete(); // ⚠️ must delete first
            $experience->delete();
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (NESTED SAFE)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $experiences, $resumeId)
    {
        if (empty($experiences)) {
            return false;
        }

        DB::beginTransaction();

        try {
            $now = now();
            $userId = Auth::id();

            foreach ($experiences as $exp) {

                $experience = $this->model->create([
                    'resume_id'   => $resumeId,
                    'designation' => $exp['designation'],
                    'company'     => $exp['company'],
                    'location'    => $exp['location'] ?? null,
                    'start_date'  => $exp['start_date'],
                    'end_date'    => $exp['end_date'] ?? null,
                    'is_current'  => $exp['is_current'],
                    'status'      => $exp['status'] ?? Experience::STATUS_ACTIVE,

                    'created_by'  => $userId,
                    'updated_by'  => $userId,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);

                // 🔥 DETAILS INSERT
                if (!empty($exp['details'])) {

                    $details = array_map(function ($detail) use ($experience, $now) {
                        return [
                            'experience_id' => $experience->id,
                            'description'   => $detail['description'],
                            'status'        => $detail['status'] ?? Experience::STATUS_ACTIVE,
                            'created_at'    => $now,
                            'updated_at'    => $now,
                        ];
                    }, $exp['details']);

                    $experience->details()->insert($details);
                }
            }

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
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