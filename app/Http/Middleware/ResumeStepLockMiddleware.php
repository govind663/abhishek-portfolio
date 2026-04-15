<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Resume;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResumeStepLockMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $resumeId = $request->route('id');

        if (!$resumeId) {
            return $next($request);
        }

        /**
         * 🔐 OWNER VALIDATION (FIXED: created_by)
         */
        $resume = Resume::where('id', $resumeId)
            ->where('created_by', $request->user()?->id)
            ->first();

        if (!$resume) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => 'Resume not found or unauthorized'
            ], 403));
        }

        $step = $this->detectStep($request);

        if (!$step) {
            return $next($request);
        }

        /**
         * 🔥 DB SOURCE OF TRUTH
         */
        $currentStep = (int) ($resume->current_step ?? 1);

        /**
         * 🔒 BLOCK: Skip forward
         */
        if ($step > $currentStep + 1) {
            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => "Step {$step} is locked. Complete Step {$currentStep} first."
            ], 422));
        }

        /**
         * 🔒 BLOCK: accessing future incomplete step
         */
        if ($step > $currentStep) {
            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => "You must complete Step {$currentStep} first."
            ], 422));
        }

        /**
         * 🔒 DATA CONSISTENCY CHECK
         */
        if (!$this->isStepValid($resume, $step)) {
            throw new HttpResponseException(response()->json([
                'status'  => false,
                'message' => "Step {$step} prerequisites not completed in database."
            ], 422));
        }

        return $next($request);
    }

    /**
     * ✅ STEP DETECTION (FIXED ROUTE NAMES)
     */
    private function detectStep(Request $request): ?int
    {
        $routeName = $request->route()?->getName();

        return match ($routeName) {
            'resume.step1', 'resume.update.step1' => 1,
            'resume.step2', 'resume.update.step2' => 2,
            'resume.step3', 'resume.update.step3' => 3,
            'resume.step4', 'resume.update.step4' => 4,
            default => null,
        };
    }

    /**
     * ✅ DB VALIDATION (SOURCE OF TRUTH)
     */
    private function isStepValid(Resume $resume, int $step): bool
    {
        return match ($step) {

            1 => filled($resume->name) && filled($resume->email),

            2 => $resume->educations()->exists(),

            3 => $resume->skills()->exists(),

            4 => $resume->experiences()->exists(),

            default => false,
        };
    }
}