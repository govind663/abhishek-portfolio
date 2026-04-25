<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class Resume extends Model
{
    use SoftDeletes, UserTracking;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'resumes';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'title',
        'summary',
        'location',
        'phone',
        'email',
        'status',
        'current_step',
        'is_completed',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'is_completed' => 'boolean',
        'current_step' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | DEFAULT VALUES
    |--------------------------------------------------------------------------
    */
    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'current_step' => 1,
        'is_completed' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT (🔥 CASCADE + RESTORE FIX)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        // ✅ Soft delete cascade
        static::deleting(function ($resume) {

            $resume->educations()->delete();
            $resume->skills()->delete();

            $resume->experiences()->each(function ($exp) {
                $exp->details()->delete();
                $exp->delete();
            });
        });

        // ✅ Restore cascade (PRO LEVEL FIX)
        static::restoring(function ($resume) {

            $resume->educations()->withTrashed()->restore();
            $resume->skills()->withTrashed()->restore();

            $resume->experiences()->withTrashed()->each(function ($exp) {
                $exp->restore();
                $exp->details()->withTrashed()->restore();
            });
        });
    }

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

    public function educations()
    {
        return $this->hasMany(Education::class)->latest('id');
    }

    public function skills()
    {
        return $this->hasMany(TechnicalSkill::class)->latest('id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class)->latest('id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (SAFE + CLEAN)
    |--------------------------------------------------------------------------
    */

    public function getNameAttribute($value)
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

    public function isCompleted(): bool
    {
        return (bool) $this->is_completed;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}