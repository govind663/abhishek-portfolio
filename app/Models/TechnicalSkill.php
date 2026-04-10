<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class TechnicalSkill extends Model
{
    use SoftDeletes, UserTracking;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */
    protected $table = 'technical_skills';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
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
        'deleted_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS 🔥
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        // future-ready (agar multiple icons ya config store karo)
        'icon_path' => 'string',
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
        return $query->orderBy('id', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS 🔥
    |--------------------------------------------------------------------------
    */
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS 🔥 (SVG RENDER HELPER)
    |--------------------------------------------------------------------------
    */
    public function getSvgIconAttribute()
    {
        if (!$this->icon_path) {
            return null;
        }

        return '<svg width="18" height="18" fill="' . $this->icon_fill . '" viewBox="' . $this->icon_viewbox . '">
                    <path d="' . $this->icon_path . '"></path>
                </svg>';
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL)
    |--------------------------------------------------------------------------
    */
    public function getSkillNameAttribute($value)
    {
        return ucfirst($value);
    }
}