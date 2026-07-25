<?php

namespace App\Repositories;

use App\Models\Resume;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ResumeRepository
{
    /**
     * Constructor.
     */
    public function __construct(
        protected Resume $model
    ) {}

    /*
    |--------------------------------------------------------------------------
    | INDEX LIST
    |--------------------------------------------------------------------------
    */
    public function all(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find(int|string $id): Resume
    {
        return $this->model
            ->where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND WITH RELATIONS
    |--------------------------------------------------------------------------
    */
    public function findWithRelations(int|string $id): Resume
    {
        return $this->model
            ->with([
                'educations',
                'skills',
                'experiences.details',
            ])
            ->where('created_by', Auth::id())
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(array $data): Resume
    {
        try {

            $data['created_by'] ??= Auth::id();
            $data['updated_by'] = Auth::id();

            $resume = $this->model->create($data);

            Log::info('Resume Created', [
                'resume_id' => $resume->id,
                'user_id'   => Auth::id(),
            ]);

            return $resume;

        } catch (Throwable $e) {

            $this->logError(
                'Resume Create Failed',
                $e
            );

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

            $data['updated_by'] = Auth::id();

            /*
            |--------------------------------------------------------------------------
            | Remove only null values
            |--------------------------------------------------------------------------
            */
            $data = array_filter(
                $data,
                static fn ($value) => !is_null($value)
            );

            $resume->update($data);

            Log::info('Resume Updated', [
                'resume_id' => $resume->id,
                'user_id'   => Auth::id(),
            ]);

            return $resume->refresh();

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

            if ($resume->trashed()) {
                return false;
            }

            $deleted = $resume->delete();

            Log::info('Resume Deleted', [
                'resume_id' => $resume->id,
                'user_id'   => Auth::id(),
            ]);

            return $deleted;

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
        string $message,
        Throwable $e,
        array $context = []
    ): void {

        Log::error($message, array_merge($context, [

            'user_id' => Auth::id(),

            'message' => $e->getMessage(),

            'file' => $e->getFile(),

            'line' => $e->getLine(),

            // 'trace' => $e->getTraceAsString(),

        ]));
    }
}