<?php

namespace App\Repositories;

use App\Models\ExperienceDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ExperienceDetailRepository
{
    public function __construct(
        protected ExperienceDetail $model
    ) {}

    /*
    |--------------------------------------------------------------------------
    | GET ALL (PAGINATED)
    |--------------------------------------------------------------------------
    */
    public function all($perPage = 10)
    {
        return $this->model
            ->latest('id')
            ->paginate($perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */
    public function find($id): ExperienceDetail
    {
        return $this->model->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create(array $data): ExperienceDetail
    {
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        return $this->model->create($data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update($id, array $data): ExperienceDetail
    {
        $detail = $this->find($id);

        $data['updated_by'] = Auth::id();

        $detail->update($data);

        return $detail->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function delete($id): bool
    {
        return $this->find($id)->delete();
    }

    /*
    |--------------------------------------------------------------------------
    | GET BY EXPERIENCE (NEVER NULL)
    |--------------------------------------------------------------------------
    */
    public function getByExperience($experienceId): Collection
    {
        return $this->model
            ->where('experience_id', $experienceId)
            ->latest('id')
            ->get() ?? collect();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE BY EXPERIENCE
    |--------------------------------------------------------------------------
    */
    public function deleteByExperience($experienceId): bool
    {
        $this->model
            ->where('experience_id', $experienceId)
            ->delete();

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | BULK INSERT (SMART FILTER + SAFE)
    |--------------------------------------------------------------------------
    */
    public function bulkInsert(array $details, $experienceId): bool
    {
        if (empty($details)) return false;

        $now = now();
        $userId = Auth::id();

        $data = [];

        foreach ($details as $detail) {

            // skip empty rows
            if (empty($detail['description'])) continue;

            $data[] = [
                'experience_id' => $experienceId,
                'description'   => $detail['description'],
                'status'        => $detail['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                'created_by'    => $userId,
                'updated_by'    => $userId,
                'created_at'    => $now,
                'updated_at'    => $now,
            ];
        }

        if (empty($data)) return false;

        return $this->model->insert($data);
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL SYNC (ULTRA SAFE VERSION)
    |--------------------------------------------------------------------------
    */
    public function sync(Collection $existing, array $newData, $experienceId): bool
    {
        $userId = Auth::id();

        $existingIds = $existing->pluck('id')->toArray();
        $newIds = collect($newData)->pluck('id')->filter()->toArray();

        /*
        |--------------------------------------------------------------------------
        | DELETE REMOVED
        |--------------------------------------------------------------------------
        */
        $deleteIds = array_diff($existingIds, $newIds);

        if (!empty($deleteIds)) {
            $this->model
                ->whereIn('id', $deleteIds)
                ->where('experience_id', $experienceId)
                ->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE + CREATE
        |--------------------------------------------------------------------------
        */
        foreach ($newData as $item) {

            if (empty($item['description'])) continue;

            if (!empty($item['id'])) {

                $this->model
                    ->where('id', $item['id'])
                    ->where('experience_id', $experienceId)
                    ->update([
                        'description' => $item['description'],
                        'status'      => $item['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                        'updated_by'  => $userId,
                    ]);

            } else {

                $this->model->create([
                    'experience_id' => $experienceId,
                    'description'   => $item['description'],
                    'status'        => $item['status'] ?? ExperienceDetail::STATUS_ACTIVE,
                    'created_by'    => $userId,
                    'updated_by'    => $userId,
                ]);
            }
        }

        return true;
    }
}