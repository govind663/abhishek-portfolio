<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class ContactInfo extends Model
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
    protected $table = 'contact_infos';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'phone',
        'email',
        'address',
        'working_hours',
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

    // ✅ Active records
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // ✅ Latest by ID
    public function scopeLatestId($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPhoneAttribute($value)
    {
        return $value ?: '9004763926';
    }

    public function getEmailAttribute($value)
    {
        return $value ?: 'codingthunder1997@gmail.com';
    }

    public function getAddressAttribute($value)
    {
        return $value ?: 'Not Available';
    }
}