<?php

namespace App\Services;

use App\Models\ContactInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContactService
{
    public function store(array $data): ContactInfo
    {
        DB::beginTransaction();

        try {
            $contact = ContactInfo::create($data);

            DB::commit();
            return $contact;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Contact Store Failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function update(ContactInfo $contact, array $data): ContactInfo
    {
        DB::beginTransaction();

        try {
            $contact->update($data);

            DB::commit();
            return $contact;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Contact Update Failed', [
                'id' => $contact->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    public function delete(ContactInfo $contact): bool
    {
        DB::beginTransaction();

        try {
            $contact->delete();

            DB::commit();
            return true;

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Contact Delete Failed', [
                'id' => $contact->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}