<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class ExperienceDetail extends Model
{
    use SoftDeletes, UserTracking;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'experience_details';

    protected $fillable = [
        'experience_id',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE,
    ];

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS (FIXED)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        // ✅ creating + updating दोनों cover
        static::saving(function ($model) {
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
    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (IMPROVED)
    |--------------------------------------------------------------------------
    */
    public function getDescriptionAttribute($value)
    {
        return $this->formatText($value);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS (CLEAN INPUT)
    |--------------------------------------------------------------------------
    */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value
            ? trim(preg_replace('/\s+/', ' ', (string) $value))
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER
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