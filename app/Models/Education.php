<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class Education extends Model
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
    protected $table = 'educations';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
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
    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (OPTIONAL)
    |--------------------------------------------------------------------------
    */
    public function getDegreeAttribute($value)
    {
        return ucfirst($value);
    }

    public function getInstitutionAttribute($value)
    {
        return ucfirst($value);
    }
}