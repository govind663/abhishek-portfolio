<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class Experience extends Model
{
    use SoftDeletes, UserTracking;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'experiences';

    protected $fillable = [
        'resume_id',
        'designation',
        'company',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
        'is_current' => false,
    ];

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS (FIXED)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::saving(function ($model) {

            // ✅ If current job → remove end_date
            if ($model->is_current) {
                $model->end_date = null;
            }

            // ✅ Ensure status fallback
            if (empty($model->status)) {
                $model->status = self::STATUS_ACTIVE;
            }
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
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    public function details()
    {
        return $this->hasMany(ExperienceDetail::class)->latest('id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // ✅ Duration (robust)
    public function getDurationAttribute(): ?string
    {
        if (!$this->start_date) {
            return null;
        }

        $start = $this->start_date;

        if ($this->is_current) {
            return $start->format('M Y') . ' - Present';
        }

        if ($this->end_date) {
            return $start->format('M Y') . ' - ' . $this->end_date->format('M Y');
        }

        return $start->format('M Y');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (FIXED FORMATTING)
    |--------------------------------------------------------------------------
    */
    public function getCompanyAttribute($value)
    {
        return $this->formatText($value);
    }

    public function getDesignationAttribute($value)
    {
        return $this->formatText($value);
    }

    public function getLocationAttribute($value)
    {
        return $this->formatText($value);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER (COMMON FORMATTER)
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