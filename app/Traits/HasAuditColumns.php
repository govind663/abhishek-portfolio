<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasAuditColumns
{
    protected static function bootHasAuditColumns(): void
    {
        static::creating(function ($model) {

            $userId = Auth::id() ?? 1; // ✅ fallback

            $model->created_by = $model->created_by ?? $userId;
            $model->updated_by = $model->updated_by ?? $userId;
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id() ?? 1;
        });

        static::deleting(function ($model) {
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($model))) {

                if (empty($model->deleted_by)) {
                    $model->deleted_by = Auth::id() ?? 1;
                    $model->saveQuietly();
                }

            }
        });
    }
}