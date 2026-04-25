<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class ResumeDraft extends Model
{
    use SoftDeletes, UserTracking;

    protected $table = 'resume_drafts';

    protected $fillable = [
        'resume_id',
        'data',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeForResume($query, $resumeId)
    {
        return $query->where('resume_id', $resumeId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
    public static function getDraft($resumeId)
    {
        return self::where('resume_id', $resumeId)->first();
    }
}