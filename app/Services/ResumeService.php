<?php

namespace App\Services;

use App\Models\Resume;
use App\Repositories\EducationRepository;
use App\Repositories\ExperienceRepository;
use App\Repositories\ResumeRepository;
use App\Repositories\TechnicalSkillRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ResumeService
{
    /**
     * Constructor
     */
    public function __construct(
        protected ResumeRepository $resumeRepository,
        protected EducationRepository $educationRepository,
        protected TechnicalSkillRepository $technicalSkillRepository,
        protected ExperienceRepository $experienceRepository,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(array $data): Resume
    {
        try {

            return DB::transaction(function () use ($data) {

                $resume = $this->resumeRepository->create([
                    'name'        => $data['name'],
                    'title'       => $data['title'] ?? null,
                    'email'       => $data['email'] ?? null,
                    'phone'       => $data['phone'] ?? null,
                    'location'    => $data['location'] ?? null,
                    'summary'     => $data['summary'] ?? null,
                    'status'      => $data['status'] ?? Resume::STATUS_ACTIVE,
                    'created_by'  => Auth::id(),
                    'updated_by'  => Auth::id(),
                ]);

                if (!empty($data['educations'])) {
                    $this->educationRepository->bulkInsert(
                        $data['educations'],
                        $resume->id
                    );
                }

                if (!empty($data['skills'])) {
                    $this->technicalSkillRepository->bulkInsert(
                        $data['skills'],
                        $resume->id
                    );
                }

                if (!empty($data['experiences'])) {
                    $this->experienceRepository->bulkInsert(
                        $data['experiences'],
                        $resume->id
                    );
                }

                Log::info('Resume Created Successfully', [
                    'resume_id' => $resume->id,
                    'user_id'   => Auth::id(),
                ]);

                return $resume->load([
                    'educations',
                    'skills',
                    'experiences.details',
                ]);
            });

        } catch (Throwable $e) {

            $this->logError('Resume Store Failed', $e);

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Resume $resume,
        array $data
    ): Resume {

        try {

            return DB::transaction(function () use ($resume, $data) {

                $this->resumeRepository->update($resume, [
                    'name'       => $data['name'],
                    'title'      => $data['title'] ?? null,
                    'email'      => $data['email'] ?? null,
                    'phone'      => $data['phone'] ?? null,
                    'location'   => $data['location'] ?? null,
                    'summary'    => $data['summary'] ?? null,
                    'status'     => $data['status'] ?? Resume::STATUS_ACTIVE,
                    'updated_by' => Auth::id(),
                ]);

                $this->educationRepository->sync(
                    $this->educationRepository->getByResume($resume->id),
                    $data['educations'] ?? [],
                    $resume->id
                );

                $this->technicalSkillRepository->sync(
                    $this->technicalSkillRepository->getByResume($resume->id),
                    $data['skills'] ?? [],
                    $resume->id
                );

                $this->experienceRepository->sync(
                    $this->experienceRepository->getByResume($resume->id),
                    $data['experiences'] ?? [],
                    $resume->id
                );

                Log::info('Resume Updated Successfully', [
                    'resume_id' => $resume->id,
                    'user_id'   => Auth::id(),
                ]);

                return $resume->fresh([
                    'educations',
                    'skills',
                    'experiences.details',
                ]);
            });

        } catch (Throwable $e) {

            $this->logError(
                'Resume Update Failed',
                $e,
                [
                    'resume_id' => $resume->id,
                ]
            );

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(Resume $resume): bool
    {
        try {

            return DB::transaction(function () use ($resume) {

                /*
                |--------------------------------------------------------------------------
                | NOTE
                |--------------------------------------------------------------------------
                |
                | Resume Model already performs cascade delete
                | for Education, Skills, Experience and
                | Experience Details.
                |
                */

                $deleted = $this->resumeRepository->delete($resume);

                Log::info('Resume Deleted Successfully', [
                    'resume_id' => $resume->id,
                    'user_id'   => Auth::id(),
                ]);

                return $deleted;
            });

        } catch (Throwable $e) {

            $this->logError(
                'Resume Delete Failed',
                $e,
                [
                    'resume_id' => $resume->id,
                ]
            );

            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE LOGGER
    |--------------------------------------------------------------------------
    */

    private function logError(
        string $title,
        Throwable $e,
        array $context = []
    ): void {

        Log::error($title, array_merge($context, [

            'user_id' => Auth::id(),

            'message' => $e->getMessage(),

            'file' => $e->getFile(),

            'line' => $e->getLine(),

            //'trace' => $e->getTraceAsString(),

        ]));
    }
}