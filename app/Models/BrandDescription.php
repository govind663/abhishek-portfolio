<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class BrandDescription extends Model
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
    protected $table = 'brand_descriptions';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'logo',
        'title',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS
    |--------------------------------------------------------------------------
    */
    protected $appends = ['logo_url'];

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

    // 🔥 Proper logo URL (best practice)
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('backend/assets/img/logo/abhishek-potfolio_black_logo.webp');
        }

        if (filter_var($this->logo, FILTER_VALIDATE_URL)) {
            return $this->logo;
        }

        return asset('storage/' . $this->logo);
    }

    public function getTitleAttribute($value)
    {
        return $value ?: 'About Me';
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?: 'I create smart, scalable, and user-focused web solutions. Passionate about clean architecture, smooth performance, and building applications that solve real problems with simplicity and efficiency.';
    }
}