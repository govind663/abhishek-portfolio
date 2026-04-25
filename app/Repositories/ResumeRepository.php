<?php

namespace App\Repositories;

use App\Models\Resume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
            ->where('created_by', Auth::id())
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND (SAFE)
    |--------------------------------------------------------------------------
    */
    public function find($id): Resume
    {
        return $this->model
            ->where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND WITH RELATIONS (SAFE)
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
            ->where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY USER (OPTIONAL)
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
    | GET ACTIVE
    |--------------------------------------------------------------------------
    */
    public function active($perPage = 10)
    {
        return $this->model
            ->active()
            ->where('created_by', Auth::id())
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(array $data): Resume
    {
        try {

            $data['created_by'] = $data['created_by'] ?? Auth::id();
            $data['updated_by'] = Auth::id();

            $resume = $this->model->create($data);

            Log::info('Resume Created', ['id' => $resume->id]);

            return $resume;

        } catch (\Throwable $e) {

            Log::error('Resume Create Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE (SAFE + FLEXIBLE)
    |--------------------------------------------------------------------------
    */
    public function update($resume, array $data): Resume
    {
        try {

            if (!$resume instanceof Resume) {
                $resume = $this->find($resume);
            }

            $data['updated_by'] = Auth::id();

            // ⚠️ IMPORTANT: null remove, but keep false/0
            $data = array_filter($data, fn($v) => $v !== null);

            if (!empty($data)) {
                $resume->update($data);
            }

            Log::info('Resume Updated', ['id' => $resume->id]);

            return $resume->refresh();

        } catch (\Throwable $e) {

            Log::error('Resume Update Failed', [
                'resume_id' => $resume instanceof Resume ? $resume->id : $resume,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE SINGLE (SOFT DELETE SAFE)
    |--------------------------------------------------------------------------
    */
    public function delete(Resume $resume): bool
    {
        try {

            // ⚠️ prevent double delete
            if ($resume->trashed()) {
                return false;
            }

            Log::info('Resume Delete', ['id' => $resume->id]);

            return $resume->delete();

        } catch (\Throwable $e) {

            Log::error('Resume Delete Failed', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE WITH RELATIONS (SAFE CASCADE)
    |--------------------------------------------------------------------------
    */
    public function deleteWithRelations(Resume $resume): bool
    {
        try {

            if ($resume->trashed()) {
                return false;
            }

            Log::info('Resume Delete With Relations', ['id' => $resume->id]);

            $resume->load([
                'educations',
                'skills',
                'experiences.details'
            ]);

            // 🔥 soft delete children
            $resume->educations()->delete();
            $resume->skills()->delete();

            foreach ($resume->experiences as $experience) {
                $experience->details()->delete();
                $experience->delete();
            }

            return $resume->delete();

        } catch (\Throwable $e) {

            Log::error('Delete With Relations Failed', [
                'resume_id' => $resume->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}