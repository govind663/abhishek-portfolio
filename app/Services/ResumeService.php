<?php

namespace App\Services;

use App\Models\Resume;
use App\Repositories\ResumeRepository;
use App\Repositories\EducationRepository;
use App\Repositories\TechnicalSkillRepository;
use App\Repositories\ExperienceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ResumeService
{
    public function __construct(
        protected ResumeRepository $resumeRepository,
        protected EducationRepository $educationRepository,
        protected TechnicalSkillRepository $technicalSkillRepository,
        protected ExperienceRepository $experienceRepository,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | STEP 1 - CREATE
    |--------------------------------------------------------------------------
    */
    public function storeStep1(array $data): Resume
    {
        try {
            return DB::transaction(function () use ($data) {

                $resume = $this->resumeRepository->create([
                    'name'         => $data['name'],
                    'title'        => $data['title'] ?? null,
                    'summary'      => $data['summary'] ?? null,
                    'location'     => $data['location'] ?? null,
                    'phone'        => $data['phone'] ?? null,
                    'email'        => $data['email'] ?? null,
                    'status'       => $data['status'] ?? Resume::STATUS_ACTIVE,
                    'created_by'   => Auth::id(),
                    'current_step' => 1,
                    'is_completed' => false,
                ]);

                Log::info('Step1 Success', ['resume_id' => $resume->id]);

                return $resume;
            });
        } catch (\Throwable $e) {
            Log::error('Step1 Failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */
    public function storeStep2(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $educations = $data['educations'] ?? [];

                if (!empty($educations)) {
                    $this->educationRepository->bulkInsert($educations, $resume->id);
                }

                $resume->update([
                    'current_step' => max($resume->current_step, 2)
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Step2 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    |--------------------------------------------------------------------------
    */
    public function storeStep3(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $skills = $data['skills'] ?? [];

                if (!empty($skills)) {
                    $this->technicalSkillRepository->bulkInsert($skills, $resume->id);
                }

                $resume->update([
                    'current_step' => max($resume->current_step, 3)
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Step3 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4
    |--------------------------------------------------------------------------
    */
    public function storeStep4(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $experiences = $data['experiences'] ?? [];

                if (!empty($experiences)) {
                    $this->experienceRepository->bulkInsert($experiences, $resume->id);
                }

                $resume->update([
                    'current_step' => 4,
                    'is_completed' => true
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Step4 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 1
    |--------------------------------------------------------------------------
    */
    public function updateStep1(int $resumeId, array $data): Resume
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                return $this->resumeRepository->update($resume, $data);
            });
        } catch (\Throwable $e) {
            Log::error('Update Step1 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 2
    |--------------------------------------------------------------------------
    */
    public function updateStep2(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $existing = $this->educationRepository->getByResume($resume->id);

                $this->educationRepository->sync(
                    $existing,
                    $data['educations'] ?? [],
                    $resume->id
                );

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Update Step2 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 3
    |--------------------------------------------------------------------------
    */
    public function updateStep3(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $existing = $this->technicalSkillRepository->getByResume($resume->id);

                $this->technicalSkillRepository->sync(
                    $existing,
                    $data['skills'] ?? [],
                    $resume->id
                );

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Update Step3 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 4
    |--------------------------------------------------------------------------
    */
    public function updateStep4(int $resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $existing = $this->experienceRepository->getByResume($resume->id);

                $this->experienceRepository->sync(
                    $existing,
                    $data['experiences'] ?? [],
                    $resume->id
                );

                $resume->update([
                    'is_completed' => true
                ]);

                return true;
            });
        } catch (\Throwable $e) {
            Log::error('Update Step4 Failed', ['resume_id' => $resumeId]);
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

                if ($resume->trashed()) {
                    return false;
                }

                $this->educationRepository->deleteByResume($resume->id);
                $this->technicalSkillRepository->deleteByResume($resume->id);
                $this->experienceRepository->deleteByResume($resume->id);

                return $this->resumeRepository->delete($resume);
            });
        } catch (\Throwable $e) {
            Log::error('Delete Failed', ['resume_id' => $resume->id]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
    protected function validateResume(int $resumeId): Resume
    {
        return Resume::where('id', $resumeId)
            ->where('created_by', Auth::id())
            ->firstOrFail();
    }
}