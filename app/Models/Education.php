<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class Education extends Model
{
    use SoftDeletes, UserTracking;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'educations';

    protected $fillable = [
        'resume_id',
        'degree',
        'field',
        'institution',
        'university',
        'location',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeLatestId($query)
    {
        return $query->latest('id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    public function getDegreeAttribute($value)
    {
        return $this->formatText($value);
    }

    public function getInstitutionAttribute($value)
    {
        return $this->formatText($value);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER (FIXED)
    |--------------------------------------------------------------------------
    */
    private function formatText($value)
    {
        if (!$value) {
            return $value;
        }

        return collect(preg_split('/\s+/', strtolower(trim($value))))
            ->map(fn($word) => ucfirst($word))
            ->implode(' ');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}