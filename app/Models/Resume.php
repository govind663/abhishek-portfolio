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
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

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
        'deleted_by'
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

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function skills()
    {
        return $this->hasMany(TechnicalSkill::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL)
    |--------------------------------------------------------------------------
    */

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}