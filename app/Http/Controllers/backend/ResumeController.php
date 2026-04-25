<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;

// STORE REQUESTS
use App\Http\Requests\Backend\Resume\StoreResumeStep1Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep2Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep3Request;
use App\Http\Requests\Backend\Resume\StoreResumeStep4Request;

// UPDATE REQUESTS
use App\Http\Requests\Backend\Resume\UpdateResumeStep1Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep2Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep3Request;
use App\Http\Requests\Backend\Resume\UpdateResumeStep4Request;

// MODELS
use App\Models\Resume;
use App\Models\ResumeDraft;
use App\Services\ResumeService;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class ResumeController extends Controller
{
    public function __construct(
        protected ResumeService $resumeService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | HELPERS
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
            'error' => $e?->getMessage(),
            'file'  => $e?->getFile(),
            'line'  => $e?->getLine(),
        ]);

        return response()->json([
            'status'  => false,
            'message' => $message
        ], 500);
    }

    private function getResumeOrFail(int $id): Resume
    {
        return Resume::where('id', $id)
            ->where('created_by', Auth::id())
            ->firstOrFail();
    }

    private function ensureStepAllowed(int $id, int $step): void
    {
        $resume = $this->getResumeOrFail($id);

        // ✅ improved condition (edge safe)
        if ($step > ($resume->current_step + 1)) {
            throw new HttpResponseException(
                response()->json([
                    'status'  => false,
                    'message' => "Step {$step} locked. Complete previous step first."
                ], 422)
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX / CREATE / EDIT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $resumes = Resume::where('created_by', Auth::id())
            ->latest('id')
            ->paginate(10);

        return view('backend.resume.index', compact('resumes'));
    }

    public function create()
    {
        return view('backend.resume.create');
    }

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
    | STORE STEPS
    |--------------------------------------------------------------------------
    */

    public function storeStep1(StoreResumeStep1Request $request): JsonResponse
    {
        try {
            $resume = $this->resumeService->storeStep1($request->validated());

            return $this->success('Step 1 saved', [
                'resume_id' => $resume->id
            ]);

        } catch (\Throwable $e) {
            return $this->error('Step 1 failed', $e);
        }
    }

    public function storeStep2(StoreResumeStep2Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 2);

        try {
            $this->resumeService->storeStep2($id, $request->validated());

            return $this->success('Step 2 saved');

        } catch (\Throwable $e) {
            return $this->error('Step 2 failed', $e);
        }
    }

    public function storeStep3(StoreResumeStep3Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 3);

        try {
            $this->resumeService->storeStep3($id, $request->validated());

            return $this->success('Step 3 saved');

        } catch (\Throwable $e) {
            return $this->error('Step 3 failed', $e);
        }
    }

    public function storeStep4(StoreResumeStep4Request $request, int $id): JsonResponse
    {
        $this->ensureStepAllowed($id, 4);

        try {
            $this->resumeService->storeStep4($id, $request->validated());

            Cache::forget('resume_list');

            return $this->success('Resume created', [
                'redirect' => route('resume.index')
            ]);

        } catch (\Throwable $e) {
            return $this->error('Final step failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE STEPS
    |--------------------------------------------------------------------------
    */

    public function updateStep1(UpdateResumeStep1Request $request, int $id): JsonResponse
    {
        try {
            $this->getResumeOrFail($id);

            $this->resumeService->updateStep1($id, $request->validated());

            return $this->success('Step 1 updated');

        } catch (\Throwable $e) {
            return $this->error('Update step1 failed', $e);
        }
    }

    public function updateStep2(UpdateResumeStep2Request $request, int $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep2($id, $request->validated());

            return $this->success('Step 2 updated');

        } catch (\Throwable $e) {
            return $this->error('Update step2 failed', $e);
        }
    }

    public function updateStep3(UpdateResumeStep3Request $request, int $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep3($id, $request->validated());

            return $this->success('Step 3 updated');

        } catch (\Throwable $e) {
            return $this->error('Update step3 failed', $e);
        }
    }

    public function updateStep4(UpdateResumeStep4Request $request, int $id): JsonResponse
    {
        try {
            $this->resumeService->updateStep4($id, $request->validated());

            Cache::forget('resume_list');

            return $this->success('Resume updated', [
                'redirect' => route('resume.index')
            ]);

        } catch (\Throwable $e) {
            return $this->error('Update failed', $e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AUTO SAVE DRAFT
    |--------------------------------------------------------------------------
    */

    public function autoSave(Request $request, int $id): JsonResponse
    {
        try {
            $resume = $this->getResumeOrFail($id);

            $data = json_decode($request->getContent(), true);

            if (!is_array($data)) {
                $data = $request->all();
            }

            // ✅ improved filter (arrays preserved)
            $data = array_filter($data, function ($v) {
                return !(is_null($v) || $v === '' || $v === []);
            });

            if (empty($data)) {
                return response()->json(['status' => true]);
            }

            ResumeDraft::updateOrCreate(
                ['resume_id' => $resume->id],
                [
                    'data'       => $data,
                    'updated_by' => Auth::id(),
                    'created_by' => Auth::id(),
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Draft saved'
            ]);

        } catch (\Throwable $e) {

            Log::error('Draft Save Error', [
                'msg' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Draft save failed'
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET DRAFT
    |--------------------------------------------------------------------------
    */

    public function getDraft(int $id): JsonResponse
    {
        try {
            $resume = $this->getResumeOrFail($id);

            $draft = ResumeDraft::where('resume_id', $resume->id)->first();

            return response()->json([
                'status' => true,
                'data' => $draft?->data ?? []
            ]);

        } catch (\Throwable $e) {

            Log::error('Draft Fetch Error', [
                'msg' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Draft load failed'
            ], 500);
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
                ->with('message', 'Deleted successfully');

        } catch (\Throwable $e) {

            Log::error('Delete failed', ['msg' => $e->getMessage()]);

            return back()->with('error', 'Delete failed');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function downloadPdf($id)
    {
        try {
            $resume = Resume::with([
                'educations',
                'skills',
                'experiences.details'
            ])
            ->where('created_by', Auth::id())
            ->findOrFail($id);

            // ✅ safer filename
            $fileName = preg_replace('/[^A-Za-z0-9\-]/', '_', $resume->name ?? 'resume') . '.pdf';

            $pdf = Pdf::loadView('backend.resume.pdf', compact('resume'))
                ->setPaper('A4', 'portrait');

            return request()->has('preview')
                ? $pdf->stream($fileName)
                : $pdf->download($fileName);

        } catch (\Throwable $e) {

            Log::error('PDF Error', ['msg' => $e->getMessage()]);

            return back()->with('error', 'PDF failed');
        }
    }
}