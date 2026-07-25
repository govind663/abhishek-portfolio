<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Resume\StoreResumeRequest;
use App\Http\Requests\Backend\Resume\UpdateResumeRequest;
use App\Models\Resume;
use App\Services\ResumeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ResumeController extends Controller
{
    /**
     * Resume Service Instance.
     */
    protected ResumeService $resumeService;

    /**
     * Constructor.
     */
    public function __construct(ResumeService $resumeService)
    {
        $this->resumeService = $resumeService;
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(): View
    {
        $resumes = Resume::where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('backend.resume.index', compact('resumes'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(): View
    {
        return view('backend.resume.create');
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(StoreResumeRequest $request): RedirectResponse
    {
        try {

            $this->resumeService->store($request->validated());

            Cache::forget($this->cacheKey());

            return redirect()
                ->route('resume.index')
                ->with('message', 'Resume created successfully.');

        } catch (Throwable $e) {

            $this->logException('Resume Store Failed', $e);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id): View
    {
        $resume = Resume::with([
                'educations',
                'skills',
                'experiences.details',
            ])
            ->where('created_by', Auth::id())
            ->findOrFail($id);

        return view('backend.resume.edit', compact('resume'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(UpdateResumeRequest $request, string $id): RedirectResponse
    {   
        // dd($request->all()['experiences']);   

        try {

            $resume = $this->findResume($id);

            $this->resumeService->update(
                $resume,
                $request->validated()
            );

            Log::info('Resume Updated', [
                'resume_id' => $resume->id,
                'user_id'   => Auth::id(),
            ]);

            Cache::forget($this->cacheKey());

            return redirect()
                ->route('resume.index')
                ->with('message', 'Resume updated successfully.');

        } catch (Throwable $e) {

            $this->logException('Resume Update Failed', $e);

            return back()
                ->withInput()
                ->with('error', 'Update failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(string $id): RedirectResponse
    {
        try {

            $resume = $this->findResume($id);

            $this->resumeService->delete($resume);

            Cache::forget($this->cacheKey());

            return redirect()
                ->route('resume.index')
                ->with('message', 'Resume deleted successfully.');

        } catch (Throwable $e) {

            $this->logException('Resume Delete Failed', $e);

            return back()
                ->with('error', 'Delete failed!');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Find authenticated user's resume.
     */
    private function findResume(string $id): Resume
    {
        return Resume::where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /**
     * Resume cache key.
     */
    private function cacheKey(): string
    {
        return 'resume_list_' . Auth::id();
    }

    /**
     * Log application exceptions.
     */
    private function logException(string $message, Throwable $e): void
    {
        Log::error($message, [
            'user_id' => Auth::id(),
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
            'trace'   => $e->getTraceAsString(),
        ]);
    }
}