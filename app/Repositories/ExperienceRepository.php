<?php

namespace App\Repositories;

use App\Models\Experience;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\ExperienceDetailRepository;

class ExperienceRepository
{
    protected Experience $model;

    protected ExperienceDetailRepository $experienceDetailRepository;

    public function __construct(
        Experience $model,
        ExperienceDetailRepository $experienceDetailRepository
    ) {
        $this->model = $model;
        $this->experienceDetailRepository = $experienceDetailRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find(int $id): Experience
    {
        try {

            return $this->model
                ->with([
                    'details' => fn ($query) => $query->latest('id')
                ])
                ->findOrFail($id);

        } catch (\Throwable $e) {

            Log::error('Experience Find Failed', [
                'experience_id' => $id,
                'message'       => $e->getMessage(),
                'line'          => $e->getLine(),
                'file'          => $e->getFile(),
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
                ->with([
                    'details' => fn ($query) => $query
                        ->whereNull('deleted_at')
                        ->latest('id')
                ])
                ->where('resume_id', $resumeId)
                ->latest('id')
                ->get();

        } catch (\Throwable $e) {

            Log::error('Experience Fetch Failed', [
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
    public function create(array $data): Experience
    {
        try {

            return DB::transaction(function () use ($data) {

                $userId = Auth::id();

                /*
                |--------------------------------------------------------------------------
                | CHECK DUPLICATE EXPERIENCE
                |--------------------------------------------------------------------------
                */
                $exists = $this->model
                    ->where('resume_id', $data['resume_id'])
                    ->where('designation', $data['designation'] ?? null)
                    ->where('company', $data['company'] ?? null)
                    ->where('location', $data['location'] ?? null)
                    ->where('start_date', $data['start_date'] ?? null)
                    ->where('end_date', $data['end_date'] ?? null)
                    ->exists();

                if ($exists) {
                    throw new \Exception('Experience already exists for this resume.');
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE EXPERIENCE
                |--------------------------------------------------------------------------
                */
                $experience = $this->model->create([

                    'resume_id'   => $data['resume_id'],

                    'designation' => $data['designation'] ?? null,

                    'company'     => $data['company'] ?? null,

                    'location'    => $data['location'] ?? null,

                    'start_date'  => $data['start_date'] ?? null,

                    'end_date'    => $data['end_date'] ?? null,

                    'is_current'  => !empty($data['is_current']),

                    'status'      => $data['status'] ?? Experience::STATUS_ACTIVE,

                    'created_by'  => $userId,

                    'updated_by'  => $userId,

                ]);

                /*
                |--------------------------------------------------------------------------
                | EXPERIENCE DETAILS
                |--------------------------------------------------------------------------
                */
                if (!empty($data['details'])) {

                    $this->experienceDetailRepository->bulkInsert(
                        $data['details'],
                        $experience->id
                    );
                }

                Log::info('Experience Created', [

                    'experience_id' => $experience->id,
                    'resume_id'     => $experience->resume_id,

                ]);

                return $experience->load('details');

            });

        } catch (\Throwable $e) {

            Log::error('Experience Create Failed', [

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
        array $experiences,
        int $resumeId
    ): bool {

        try {

            DB::transaction(function () use ($experiences, $resumeId) {

                $userId = Auth::id();

                foreach ($experiences as $exp) {

                    /*
                    |--------------------------------------------------------------------------
                    | Skip Empty Row
                    |--------------------------------------------------------------------------
                    */
                    if (
                        empty($exp['designation']) &&
                        empty($exp['company'])
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CHECK DUPLICATE
                    |--------------------------------------------------------------------------
                    */
                    $experience = $this->model
                        ->where('resume_id', $resumeId)
                        ->where('designation', $exp['designation'] ?? null)
                        ->where('company', $exp['company'] ?? null)
                        ->where('location', $exp['location'] ?? null)
                        ->where('start_date', $exp['start_date'] ?? null)
                        ->where('end_date', $exp['end_date'] ?? null)
                        ->first();

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE EXPERIENCE
                    |--------------------------------------------------------------------------
                    */
                    if (!$experience) {

                        $experience = $this->model->create([

                            'resume_id'   => $resumeId,

                            'designation' => $exp['designation'] ?? null,

                            'company'     => $exp['company'] ?? null,

                            'location'    => $exp['location'] ?? null,

                            'start_date'  => $exp['start_date'] ?? null,

                            'end_date'    => $exp['end_date'] ?? null,

                            'is_current'  => !empty($exp['is_current']),

                            'status'      => Experience::STATUS_ACTIVE,

                            'created_by'  => $userId,

                            'updated_by'  => $userId,

                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | EXPERIENCE DETAILS
                    |--------------------------------------------------------------------------
                    */
                    if (!empty($exp['details'])) {

                        $existingDetails = $this->experienceDetailRepository
                            ->getByExperience($experience->id);

                        if ($existingDetails->isEmpty()) {

                            $this->experienceDetailRepository->bulkInsert(
                                $exp['details'],
                                $experience->id
                            );

                        } else {

                            $this->experienceDetailRepository->sync(
                                $existingDetails,
                                $exp['details'],
                                $experience->id
                            );
                        }
                    }
                }
            });

            Log::info('Experience Bulk Created', [

                'resume_id' => $resumeId,

                'count' => count($experiences),

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Bulk Create Failed', [

                'resume_id' => $resumeId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(
        int $id,
        array $data
    ): Experience {

        try {

            return DB::transaction(function () use ($id, $data) {

                $experience = $this->find($id);

                /*
                |--------------------------------------------------------------------------
                | CHECK DUPLICATE EXPERIENCE
                |--------------------------------------------------------------------------
                */
                $duplicate = $this->model
                    ->where('resume_id', $experience->resume_id)
                    ->where('designation', $data['designation'] ?? null)
                    ->where('company', $data['company'] ?? null)
                    ->where('location', $data['location'] ?? null)
                    ->where('start_date', $data['start_date'] ?? null)
                    ->where('end_date', $data['end_date'] ?? null)
                    ->where('id', '!=', $id)
                    ->exists();

                if ($duplicate) {
                    throw new \Exception('Experience already exists for this resume.');
                }

                /*
                |--------------------------------------------------------------------------
                | UPDATE EXPERIENCE
                |--------------------------------------------------------------------------
                */
                $experience->update(array_filter([

                    'designation' => $data['designation'] ?? null,

                    'company'     => $data['company'] ?? null,

                    'location'    => $data['location'] ?? null,

                    'start_date'  => $data['start_date'] ?? null,

                    'end_date'    => $data['end_date'] ?? null,

                    'is_current'  => !empty($data['is_current']),

                    'status'      => $data['status'] ?? Experience::STATUS_ACTIVE,

                    'updated_by'  => Auth::id(),

                ], fn ($value) => !is_null($value)));

                /*
                |--------------------------------------------------------------------------
                | SYNC EXPERIENCE DETAILS
                |--------------------------------------------------------------------------
                */
                $existingDetails = $this->experienceDetailRepository
                    ->getByExperience($experience->id);

                $this->experienceDetailRepository->sync(
                    $existingDetails,
                    $data['details'] ?? [],
                    $experience->id
                );

                Log::info('Experience Updated', [

                    'experience_id' => $experience->id,

                    'resume_id' => $experience->resume_id,

                ]);

                return $experience->fresh('details');

            });

        } catch (\Throwable $e) {

            Log::error('Experience Update Failed', [

                'experience_id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

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

            DB::transaction(function () use ($existing, $incoming, $resumeId) {

                $userId = Auth::id();

                /*
                |--------------------------------------------------------------------------
                | DELETE REMOVED EXPERIENCES
                |--------------------------------------------------------------------------
                */
                $oldIds = $existing->pluck('id')->toArray();

                $newIds = collect($incoming)
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                $deleteIds = array_diff($oldIds, $newIds);

                if (!empty($deleteIds)) {

                    $this->model
                        ->whereIn('id', $deleteIds)
                        ->where('resume_id', $resumeId)
                        ->get()
                        ->each(function ($experience) {

                            $this->experienceDetailRepository
                                ->deleteByExperience($experience->id);

                            $experience->delete();

                        });
                }

                /*
                |--------------------------------------------------------------------------
                | CREATE / UPDATE
                |--------------------------------------------------------------------------
                */
                foreach ($incoming as $exp) {

                    if (
                        empty($exp['designation']) &&
                        empty($exp['company'])
                    ) {
                        continue;
                    }

                    $payload = [

                        'resume_id'   => $resumeId,

                        'designation' => $exp['designation'] ?? null,

                        'company'     => $exp['company'] ?? null,

                        'location'    => $exp['location'] ?? null,

                        'start_date'  => $exp['start_date'] ?? null,

                        'end_date'    => $exp['end_date'] ?? null,

                        'is_current'  => !empty($exp['is_current']),

                        'status'      => $exp['status'] ?? Experience::STATUS_ACTIVE,

                        'updated_by'  => $userId,

                    ];

                    /*
                    |--------------------------------------------------------------------------
                    | UPDATE EXPERIENCE
                    |--------------------------------------------------------------------------
                    */
                    if (!empty($exp['id'])) {

                        $experience = $this->model
                            ->where('id', $exp['id'])
                            ->where('resume_id', $resumeId)
                            ->first();

                        if (!$experience) {
                            continue;
                        }

                        $experience->update(array_filter(
                            $payload,
                            fn ($value) => !is_null($value)
                        ));

                        $existingDetails = $this->experienceDetailRepository
                            ->getByExperience($experience->id);

                        $this->experienceDetailRepository->sync(
                            $existingDetails,
                            $exp['details'] ?? [],
                            $experience->id
                        );

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | CREATE EXPERIENCE
                        |--------------------------------------------------------------------------
                        */
                        $payload['created_by'] = $userId;

                        $experience = $this->model->create($payload);

                        if (!empty($exp['details'])) {

                            $this->experienceDetailRepository->bulkInsert(
                                $exp['details'],
                                $experience->id
                            );
                        }
                    }
                }

            });

            Log::info('Experience Sync Completed', [

                'resume_id' => $resumeId,

                'count' => count($incoming),

            ]);

            return true;

        } catch (\Throwable $e) {

            Log::error('Experience Sync Failed', [

                'resume_id' => $resumeId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

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

            return DB::transaction(function () use ($resumeId) {

                $experiences = $this->getByResume($resumeId);

                foreach ($experiences as $experience) {

                    $this->experienceDetailRepository->deleteByExperience(
                        $experience->id
                    );

                    $experience->delete();

                }

                Log::info('Experience Deleted By Resume', [

                    'resume_id' => $resumeId,

                    'count' => $experiences->count(),

                ]);

                return true;

            });

        } catch (\Throwable $e) {

            Log::error('Experience Delete By Resume Failed', [

                'resume_id' => $resumeId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

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

            return DB::transaction(function () use ($id) {

                $experience = $this->find($id);

                /*
                |--------------------------------------------------------------------------
                | DELETE EXPERIENCE DETAILS
                |--------------------------------------------------------------------------
                */
                $this->experienceDetailRepository->deleteByExperience(
                    $experience->id
                );

                /*
                |--------------------------------------------------------------------------
                | DELETE EXPERIENCE
                |--------------------------------------------------------------------------
                */
                $experience->delete();

                Log::info('Experience Deleted', [

                    'experience_id' => $experience->id,

                    'resume_id' => $experience->resume_id,

                ]);

                return true;

            });

        } catch (\Throwable $e) {

            Log::error('Experience Delete Failed', [

                'experience_id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }

}