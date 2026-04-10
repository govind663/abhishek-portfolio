<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

use App\Http\Requests\Backend\Resume\StoreResumeStep1Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep2Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep3Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep4Request;

use App\Http\Requests\Backend\Resume\UpdateResumeStep1Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep2Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep3Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep4Request;

use App\Models\Resume;
use App\Services\ResumeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ResumeController extends Controller
{
    public function __construct(
        protected ResumeService $resumeService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $resumes = Resume::active()->latestId()->get();

        return view('backend.resume.index', [
            'resumes' => $resumes
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('backend.resume.create');
    }

    /*
    |--------------------------------------------------------------------------
    | COMMON JSON RESPONSE HANDLER
    |--------------------------------------------------------------------------
    */
    private function success($message, $data = []): JsonResponse
    {
        return response()->json(array_merge([
            'status'  => true,
            'message' => $message
        ], $data));
    }

    private function error($message, $e = null): JsonResponse
    {
        Log::error($message, ['error' => $e?->getMessage()]);

        return response()->json([
            'status'  => false,
            'message' => $message
        ], 500);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE STEP 1
    |--------------------------------------------------------------------------
    */
    public function storeStep1(StoreResumeStep1Request $request): JsonResponse
    {
        try {
            $resume = $this->resumeService->storeStep1($request->validated());

            Cache::forget('resume');

            return $this->success('Step 1 saved successfully', [
                'resume_id' => $resume->id
            ]);

        } catch (\Throwable $e) {
            return $this->error('Step 1 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE STEP 2
    |--------------------------------------------------------------------------
    */
    public function storeStep2(StoreResumeStep2Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->storeStep2($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Step 2 saved successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 2 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE STEP 3
    |--------------------------------------------------------------------------
    */
    public function storeStep3(StoreResumeStep3Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->storeStep3($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Step 3 saved successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 3 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE STEP 4
    |--------------------------------------------------------------------------
    */
    public function storeStep4(StoreResumeStep4Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->storeStep4($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Resume completed successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 4 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $resume = Resume::with([
            'educations',
            'skills',
            'experiences.details'
        ])->findOrFail($id);

        return view('backend.resume.edit', [
            'resume' => $resume
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 1
    |--------------------------------------------------------------------------
    */
    public function updateStep1(UpdateResumeStep1Request $request, $id): JsonResponse
    {
        try {
            $resume = Resume::findOrFail($id);

            $this->resumeService->updateStep1($resume, $request->validated());

            Cache::forget('resume');

            return $this->success('Step 1 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 1 update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 2
    |--------------------------------------------------------------------------
    */
    public function updateStep2(UpdateResumeStep2Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep2($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Step 2 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 2 update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 3
    |--------------------------------------------------------------------------
    */
    public function updateStep3(UpdateResumeStep3Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep3($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Step 3 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 3 update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 4
    |--------------------------------------------------------------------------
    */
    public function updateStep4(UpdateResumeStep4Request $request, $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep4($id, $request->validated());

            Cache::forget('resume');

            return $this->success('Resume updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 4 update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        try {
            $resume = Resume::findOrFail($id);

            $this->resumeService->delete($resume);

            Cache::forget('resume');

            return redirect()
                ->route('resume.index')
                ->with('message', 'Resume deleted successfully');

        } catch (\Throwable $e) {
            Log::error('Resume delete failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Delete failed!');
        }
    }
}