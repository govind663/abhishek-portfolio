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
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

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
        $resumes = Resume::where('created_by', Auth::id())
            ->latest('id')
            ->paginate(10);

        return view('backend.resume.index', compact('resumes'));
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
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit($id)
    {
        $resume = Resume::with([
            'educations',
            'skills',
            'experiences.details'
        ])
        ->where('created_by', Auth::id())
        ->findOrFail($id);

        return view('backend.resume.edit', compact('resume'));
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE HELPERS
    |--------------------------------------------------------------------------
    */
    private function success(string $message, array $data = []): JsonResponse
    {
        return response()->json(array_merge([
            'status'  => true,
            'message' => $message
        ], $data));
    }

    private function error(string $message, \Throwable $e = null): JsonResponse
    {
        Log::error($message, [
            'message' => $e?->getMessage(),
            'file'    => $e?->getFile(),
            'line'    => $e?->getLine(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => $message
        ], 500);
    }

    /*
    |--------------------------------------------------------------------------
    | 🔐 GET RESUME WITH OWNERSHIP CHECK
    |--------------------------------------------------------------------------
    */
    private function getResumeOrFail(int $resumeId): Resume
    {
        return Resume::where('id', $resumeId)
            ->where('created_by', Auth::id())
            ->firstOrFail();
    }

    /*
    |--------------------------------------------------------------------------
    | STEP LOCK SYSTEM (DB BASED)
    |--------------------------------------------------------------------------
    */
    private function ensureStepAllowed(int $resumeId, int $step): void
    {
        $resume = $this->getResumeOrFail($resumeId);

        // 🔥 DB based step control
        if ($step > ($resume->current_step + 1)) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => false,
                    'message' => "Step {$step} is locked. Complete Step {$resume->current_step} first"
                ], 422)
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1
    |--------------------------------------------------------------------------
    */
    public function storeStep1(StoreResumeStep1Request $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $data['created_by'] = Auth::id();

            // 🔥 INIT STEP TRACKING
            $data['current_step'] = 1;
            $data['is_completed'] = 0;

            $resume = $this->resumeService->storeStep1($data);

            return $this->success('Step 1 saved successfully', [
                'resume_id' => $resume->id
            ]);

        } catch (\Throwable $e) {
            return $this->error('Step 1 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    */
    public function storeStep2(StoreResumeStep2Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 2);

        try {
            $this->resumeService->storeStep2($id, $request->validated());

            // ✅ UPDATE STEP
            Resume::where('id', $id)->update(['current_step' => 2]);

            return $this->success('Step 2 saved successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 2 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    |--------------------------------------------------------------------------
    */
    public function storeStep3(StoreResumeStep3Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 3);

        try {
            $this->resumeService->storeStep3($id, $request->validated());

            Resume::where('id', $id)->update(['current_step' => 3]);

            return $this->success('Step 3 saved successfully');

        } catch (\Throwable $e) {
            return $this->error('Step 3 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4
    |--------------------------------------------------------------------------
    */
    public function storeStep4(StoreResumeStep4Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 4);

        try {
            $this->resumeService->storeStep4($id, $request->validated());

            // ✅ FINAL COMPLETE
            Resume::where('id', $id)->update([
                'current_step' => 4,
                'is_completed' => 1
            ]);

            Cache::forget('resume_list');

            return $this->success('Resume created successfully', [
                'redirect' => route('resume.index')
            ]);

        } catch (\Throwable $e) {
            return $this->error('Final submission failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 1
    |--------------------------------------------------------------------------
    */
    public function updateStep1(UpdateResumeStep1Request $request, int $id): JsonResponse
    {
        $this->getResumeOrFail($id);

        try {
            $this->resumeService->updateStep1($id, $request->validated());

            return $this->success('Step 1 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Update Step 1 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 2
    |--------------------------------------------------------------------------
    */
    public function updateStep2(UpdateResumeStep2Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 2);

        try {
            $this->resumeService->updateStep2($id, $request->validated());

            return $this->success('Step 2 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Update Step 2 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 3
    |--------------------------------------------------------------------------
    */
    public function updateStep3(UpdateResumeStep3Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 3);

        try {
            $this->resumeService->updateStep3($id, $request->validated());

            return $this->success('Step 3 updated successfully');

        } catch (\Throwable $e) {
            return $this->error('Update Step 3 failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEP 4
    |--------------------------------------------------------------------------
    */
    public function updateStep4(UpdateResumeStep4Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 4);

        try {
            $this->resumeService->updateStep4($id, $request->validated());

            Resume::where('id', $id)->update([
                'is_completed' => 1
            ]);

            Cache::forget('resume_list');

            return $this->success('Resume updated successfully', [
                'redirect' => route('resume.index')
            ]);

        } catch (\Throwable $e) {
            return $this->error('Update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(int $id)
    {
        try {
            $resume = $this->getResumeOrFail($id);

            $this->resumeService->delete($resume);

            Cache::forget('resume_list');

            return redirect()
                ->route('resume.index')
                ->with('message', 'Resume deleted successfully');

        } catch (\Throwable $e) {
            Log::error('Resume delete failed', [
                'message' => $e?->getMessage(),
                'file'    => $e?->getFile(),
                'line'    => $e?->getLine(),
            ]);

            return back()->with('error', 'Delete failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 📄 DOWNLOAD / PREVIEW RESUME PDF (UPGRADED)
    |--------------------------------------------------------------------------
    */
    public function downloadPdf($id)
    {
        try {

            // 🔐 Secure + full eager loading
            $resume = Resume::with([
                'educations',
                'skills',
                'experiences.details'
            ])
            ->where('created_by', Auth::id())
            ->findOrFail($id);

            // 🔥 Clean filename (safe)
            $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $resume->name) . '_resume.pdf';

            // 🔥 Generate PDF
            $pdf = Pdf::loadView('backend.resume.pdf', compact('resume'))
                ->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'defaultFont' => 'DejaVu Sans'
                ]);

            // 🔥 OPTIONAL: preview OR download
            if (request()->has('preview')) {
                return $pdf->stream($fileName); // 👀 open in browser
            }

            return $pdf->download($fileName); // ⬇️ download

        } catch (\Throwable $e) {

            Log::error('Resume PDF Download Failed', [
                'resume_id' => $id,
                'user_id'   => Auth::id(),
                'error'     => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'PDF download failed!');
        }
    }
}