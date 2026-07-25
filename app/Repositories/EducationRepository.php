<?php

namespace App\Repositories;

use App\Models\Education;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class EducationRepository
{
    protected Education $model;

    public function __construct(Education $model)
    {
        $this->model = $model;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND BY ID
    |--------------------------------------------------------------------------
    */
    public function find(int $id): Education
    {
        try {

            $education = $this->model->findOrFail($id);

            Log::info('Education Found', [
                'education_id' => $education->id,
            ]);

            return $education;

        } catch (\Throwable $e) {

            Log::error('Education Find Failed', [
                'education_id' => $id,
                'message'      => $e->getMessage(),
                'line'         => $e->getLine(),
                'file'         => $e->getFile(),
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

            $educations = $this->model
                ->where('resume_id', $resumeId)
                ->latest('id')
                ->get();

            Log::info('Education List Retrieved', [
                'resume_id' => $resumeId,
                'count'     => $educations->count(),
            ]);

            return $educations;

        } catch (\Throwable $e) {

            Log::error('Education List Fetch Failed', [
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
    public function create(array $data): Education
    {
        try {

            $userId = Auth::id();

            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $education = $this->model->create($data);

            Log::info('Education Created', [
                'education_id' => $education->id,
                'resume_id'    => $education->resume_id,
            ]);

            return $education;

        } catch (\Throwable $e) {

            Log::error('Education Create Failed', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BULK CREATE
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $educations, int $resumeId): bool
    {
        try {

            $userId = Auth::id();
            $rows = [];

            foreach ($educations as $education) {

                // skip empty row
                if (
                    empty($education['degree']) &&
                    empty($education['institution'])
                ) {
                    continue;
                }

                $rows[] = [

                    'resume_id' => $resumeId,

                    'degree' =>
                        $education['degree'] ?? null,

                    'field' =>
                        $education['field'] ?? null,

                    'institution' =>
                        $education['institution'] ?? null,

                    'university' =>
                        $education['university'] ?? null,

                    'location' =>
                        $education['location'] ?? null,

                    'start_date' =>
                        $education['start_date'] ?? null,

                    'end_date' =>
                        $education['end_date'] ?? null,

                    'status' =>
                        Education::STATUS_ACTIVE,

                    'created_by' => $userId,

                    'updated_by' => $userId,

                    'created_at' => now(),

                    'updated_at' => now(),
                ];

            }

            if(empty($rows)){
                return false;
            }

            $this->model->insert($rows);

            Log::info('Education Bulk Created',[
                'resume_id'=>$resumeId,
                'count'=>count($rows)
            ]);

            return true;

        }catch(\Throwable $e){

            Log::error('Education Bulk Create Failed',[
                'error'=>$e->getMessage()
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(int $id, array $data): Education
    {
        try {

            $education = $this->find($id);

            $data['updated_by'] = Auth::id();

            $education->update(array_filter(
                $data,
                fn ($value) => !is_null($value)
            ));

            Log::info('Education Updated', [
                'education_id' => $education->id,
            ]);

            return $education->refresh();

        } catch (\Throwable $e) {

            Log::error('Education Update Failed', [
                'education_id' => $id,
                'message'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC UPDATE MODE
    |--------------------------------------------------------------------------
    */
    public function sync(
        Collection $existing,
        array $incoming,
        int $resumeId
    ): bool {
        try {

            return DB::transaction(function () use ($existing, $incoming, $resumeId) {

                $userId = Auth::id();

                /*
                |--------------------------------------------------------------------------
                | DELETE REMOVED RECORDS
                |--------------------------------------------------------------------------
                */
                $oldIds = $existing
                    ->pluck('id')
                    ->toArray();

                $newIds = collect($incoming)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $deleteIds = array_diff($oldIds, $newIds);

                if (!empty($deleteIds)) {
                    $this->model
                        ->where('resume_id', $resumeId)
                        ->whereIn('id', $deleteIds)
                        ->delete();
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE / UPDATE RECORDS
                |--------------------------------------------------------------------------
                */
                foreach ($incoming as $education) {

                    // Skip empty row
                    if (
                        empty($education['degree']) &&
                        empty($education['institution'])
                    ) {
                        continue;
                    }

                    $payload = [

                        'degree'      => $education['degree'] ?? null,

                        'field'       => $education['field'] ?? null,

                        'institution' => $education['institution'] ?? null,

                        'university'  => $education['university'] ?? null,

                        'location'    => $education['location'] ?? null,

                        'start_date'  => $education['start_date'] ?? null,

                        'end_date'    => $education['end_date'] ?? null,

                        'updated_by'  => $userId,

                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE
                    |--------------------------------------------------------------------------
                    */
                    if (!empty($education['id'])) {

                        $this->model
                            ->where('resume_id', $resumeId)
                            ->where('id', $education['id'])
                            ->update($payload);

                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE
                    |--------------------------------------------------------------------------
                    */
                    $payload['resume_id'] = $resumeId;
                    $payload['created_by'] = $userId;

                    $this->model->create($payload);
                }

                Log::info('Education Sync Completed', [
                    'resume_id' => $resumeId,
                    'total'     => count($incoming),
                    'deleted'   => count($deleteIds),
                ]);

                return true;
            });

        } catch (\Throwable $e) {

            Log::error('Education Sync Failed', [

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

            Log::info('Education Deleted By Resume', [
                'resume_id' => $resumeId,
                'count'     => $count,
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Education Delete Failed', [
                'resume_id' => $resumeId,
                'message'   => $e->getMessage(),
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

            $education = $this->find($id);

            $education->delete();

            Log::info('Education Deleted', [
                'education_id' => $id,
            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Education Delete Failed', [
                'education_id' => $id,
                'message'      => $e->getMessage(),
            ]);

            throw $e;
        }
    }

}