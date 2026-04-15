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
    | STEP 1 - BASIC INFO
    |--------------------------------------------------------------------------
    */
    public function storeStep1(array $data): Resume
    {
        try {
            return DB::transaction(function () use ($data) {

                return $this->resumeRepository->create([
                    'name'       => $data['name'],
                    'title'      => $data['title'] ?? null,
                    'summary'    => $data['summary'] ?? null,
                    'location'   => $data['location'] ?? null,
                    'phone'      => $data['phone'] ?? null,
                    'email'      => $data['email'] ?? null,
                    'status'     => $data['status'] ?? Resume::STATUS_ACTIVE,

                    'created_by' => $data['created_by'] ?? Auth::id(),

                    'current_step' => $data['current_step'] ?? 1,
                    'is_completed' => $data['is_completed'] ?? 0,
                ]);

            });
        } catch (\Throwable $e) {
            Log::error('Step1 Store Failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2 - EDUCATION
    |--------------------------------------------------------------------------
    */
    public function storeStep2($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                if (empty($data['educations'])) {
                    Log::warning('Step2 Empty Education', ['resume_id' => $resumeId]);
                    return false;
                }

                $result = $this->educationRepository->bulkInsert(
                    $data['educations'],
                    $resume->id
                );

                if ($result) {
                    $resume->update(['current_step' => max($resume->current_step, 2)]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Step2 Store Failed', ['resume_id' => $resumeId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3 - SKILLS
    |--------------------------------------------------------------------------
    */
    public function storeStep3($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                if (empty($data['skills'])) {
                    Log::warning('Step3 Empty Skills', ['resume_id' => $resumeId]);
                    return false;
                }

                $result = $this->technicalSkillRepository->bulkInsert(
                    $data['skills'],
                    $resume->id
                );

                if ($result) {
                    $resume->update(['current_step' => max($resume->current_step, 3)]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Step3 Store Failed', ['resume_id' => $resumeId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4 - EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function storeStep4($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                if (empty($data['experiences'])) {
                    Log::warning('Step4 Empty Experience', ['resume_id' => $resumeId]);
                    return false;
                }

                $result = $this->experienceRepository->bulkInsert(
                    $data['experiences'],
                    $resume->id
                );

                if ($result) {
                    $resume->update([
                        'current_step' => 4,
                        'is_completed' => 1
                    ]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Step4 Store Failed', ['resume_id' => $resumeId, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 1
    |--------------------------------------------------------------------------
    */
    public function updateStep1($resumeId, array $data): Resume
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                return $this->resumeRepository->update($resume->id, [
                    'name'     => $data['name'] ?? $resume->name,
                    'title'    => $data['title'] ?? $resume->title,
                    'summary'  => $data['summary'] ?? $resume->summary,
                    'location' => $data['location'] ?? $resume->location,
                    'phone'    => $data['phone'] ?? $resume->phone,
                    'email'    => $data['email'] ?? $resume->email,
                    'status'   => $data['status'] ?? $resume->status,
                ]);

            });
        } catch (\Throwable $e) {
            Log::error('Update Step1 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 2 (SYNC)
    |--------------------------------------------------------------------------
    */
    public function updateStep2($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $existing = $this->educationRepository->getByResume($resume->id);

                $result = $this->educationRepository->sync(
                    $existing,
                    $data['educations'] ?? [],
                    $resume->id
                );

                if ($result) {
                    $resume->update(['current_step' => max($resume->current_step, 2)]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Update Step2 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 3 (SYNC)
    |--------------------------------------------------------------------------
    */
    public function updateStep3($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $existing = $this->technicalSkillRepository->getByResume($resume->id);

                $result = $this->technicalSkillRepository->sync(
                    $existing,
                    $data['skills'] ?? [],
                    $resume->id
                );

                if ($result) {
                    $resume->update(['current_step' => max($resume->current_step, 3)]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Update Step3 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 4 (SAFE REPLACE)
    |--------------------------------------------------------------------------
    */
    public function updateStep4($resumeId, array $data): bool
    {
        try {
            return DB::transaction(function () use ($resumeId, $data) {

                $resume = $this->validateResume($resumeId);

                $this->experienceRepository->deleteByResume($resume->id);

                if (empty($data['experiences'])) {
                    return true;
                }

                $result = $this->experienceRepository->bulkInsert(
                    $data['experiences'],
                    $resume->id
                );

                if ($result) {
                    $resume->update([
                        'current_step' => 4,
                        'is_completed' => 1
                    ]);
                }

                return $result;

            });
        } catch (\Throwable $e) {
            Log::error('Update Step4 Failed', ['resume_id' => $resumeId]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE RESUME (CASCADE SAFE)
    |--------------------------------------------------------------------------
    */
    public function delete(Resume $resume): bool
    {
        try {
            return DB::transaction(function () use ($resume) {

                $this->educationRepository->deleteByResume($resume->id);
                $this->technicalSkillRepository->deleteByResume($resume->id);
                $this->experienceRepository->deleteByResume($resume->id);

                return $this->resumeRepository->delete($resume);

            });
        } catch (\Throwable $e) {
            Log::error('Delete Resume Failed', ['resume_id' => $resume->id]);
            throw $e;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find($id): Resume
    {
        return $this->resumeRepository->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND WITH RELATIONS
    |--------------------------------------------------------------------------
    */
    public function findWithRelations($id): Resume
    {
        return $this->resumeRepository->findWithRelations($id);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 INTERNAL VALIDATION (SECURE)
    |--------------------------------------------------------------------------
    */
    protected function validateResume($resumeId): Resume
    {
        try {
            return Resume::where('id', $resumeId)
                ->where('created_by', Auth::id())
                ->firstOrFail();

        } catch (\Throwable $e) {

            Log::error('Unauthorized Resume Access', [
                'resume_id' => $resumeId,
                'user_id'   => Auth::id(),
                'error'     => $e->getMessage()
            ]);

            throw $e;
        }
    }
}