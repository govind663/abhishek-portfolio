<?php

namespace App\Services;

use App\Models\Resume;
use App\Repositories\ResumeRepository;
use App\Repositories\EducationRepository;
use App\Repositories\TechnicalSkillRepository;
use App\Repositories\ExperienceRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function storeStep1(array $data)
    {
        return DB::transaction(function () use ($data) {

            return $this->resumeRepository->create([
                'name'     => $data['name'],
                'title'    => $data['title'] ?? null,
                'summary'  => $data['summary'] ?? null,
                'location' => $data['location'] ?? null,
                'phone'    => $data['phone'] ?? null,
                'email'    => $data['email'] ?? null,
                'status'   => Resume::STATUS_ACTIVE,
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2 - EDUCATION
    |--------------------------------------------------------------------------
    */
    public function storeStep2($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            return $this->educationRepository->bulkInsert(
                $data['educations'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3 - SKILLS
    |--------------------------------------------------------------------------
    */
    public function storeStep3($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            return $this->technicalSkillRepository->bulkInsert(
                $data['skills'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4 - EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function storeStep4($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            return $this->experienceRepository->bulkInsert(
                $data['experiences'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 1
    |--------------------------------------------------------------------------
    */
    public function updateStep1($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            return $this->resumeRepository->update($resume, $data);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 2
    |--------------------------------------------------------------------------
    */
    public function updateStep2($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            $this->educationRepository->deleteByResume($resume->id);

            return $this->educationRepository->bulkInsert(
                $data['educations'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 3
    |--------------------------------------------------------------------------
    */
    public function updateStep3($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            $this->technicalSkillRepository->deleteByResume($resume->id);

            return $this->technicalSkillRepository->bulkInsert(
                $data['skills'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 4
    |--------------------------------------------------------------------------
    */
    public function updateStep4($resumeId, array $data)
    {
        return DB::transaction(function () use ($resumeId, $data) {

            $resume = $this->validateResume($resumeId);

            $this->experienceRepository->deleteByResume($resume->id);

            return $this->experienceRepository->bulkInsert(
                $data['experiences'] ?? [],
                $resume->id
            );

        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE RESUME (SAFE CASCADE)
    |--------------------------------------------------------------------------
    */
    public function delete(Resume $resume)
    {
        return DB::transaction(function () use ($resume) {

            // ⚡ Let repositories handle clean cascade
            $this->educationRepository->deleteByResume($resume->id);
            $this->technicalSkillRepository->deleteByResume($resume->id);
            $this->experienceRepository->deleteByResume($resume->id);

            return $this->resumeRepository->delete($resume);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | INTERNAL HELPER
    |--------------------------------------------------------------------------
    */
    protected function validateResume($resumeId): Resume
    {
        $resume = Resume::find($resumeId);

        if (!$resume) {
            Log::error('Invalid Resume ID', ['resume_id' => $resumeId]);
            throw new \Exception('Invalid Resume ID');
        }

        return $resume;
    }
}