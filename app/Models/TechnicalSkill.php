<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;
use Illuminate\Support\HtmlString;

class TechnicalSkill extends Model
{
    use SoftDeletes, UserTracking;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';

    protected $table = 'technical_skills';

    protected $fillable = [
        'resume_id',
        'category',
        'skill_name',
        'icon_path',
        'icon_viewbox',
        'icon_fill',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'icon_path'    => 'string',
        'icon_viewbox' => 'string',
        'icon_fill'    => 'string',
    ];

    protected $attributes = [
        'status'       => self::STATUS_ACTIVE,
        'icon_viewbox' => '0 0 24 24',
        'icon_fill'    => '#000',
    ];

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS (FIXED)
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        // ✅ creating + updating both cover
        static::saving(function ($model) {
            $model->icon_viewbox = $model->icon_viewbox ?: '0 0 24 24';
            $model->icon_fill    = $model->icon_fill ?: '#000';
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

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR: SVG ICON (SECURE + SAFE)
    |--------------------------------------------------------------------------
    */
    public function getSvgIconAttribute(): ?HtmlString
    {
        if (empty($this->icon_path)) {
            return null; // or fallback SVG return kar sakte ho
        }

        return new HtmlString(
            sprintf(
                '<svg width="18" height="18" fill="%s" viewBox="%s"><path d="%s"></path></svg>',
                e($this->icon_fill),
                e($this->icon_viewbox),
                e($this->icon_path)
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (IMPROVED)
    |--------------------------------------------------------------------------
    */
    public function getSkillNameAttribute($value)
    {
        return $this->formatText($value);
    }

    public function getCategoryAttribute($value)
    {
        return $this->formatText($value);
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