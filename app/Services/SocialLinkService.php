<?php

namespace App\Services;

use App\Models\SocialLink;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SocialLinkService
{
    public function store(array $data): SocialLink
    {
        DB::beginTransaction();

        try {
            $social = SocialLink::create($data);

            DB::commit();
            return $social;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Social Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update(SocialLink $social, array $data): SocialLink
    {
        DB::beginTransaction();

        try {
            $social->update($data);

            DB::commit();
            return $social;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Social Update Failed', [
                'id' => $social->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete(SocialLink $social): bool
    {
        DB::beginTransaction();

        try {
            $social->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Social Delete Failed', [
                'id' => $social->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}