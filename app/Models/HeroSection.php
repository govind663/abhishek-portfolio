<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;

class HeroSection extends Model
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
    protected $table = 'hero_sections';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'subtitle',
        'description',
        'profile_image',
        'background_image',
        'resume_file',
        'typed_items',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS  🔥 (IMPORTANT)
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'typed_items' => 'array',
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
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getProfileImageAttribute($value)
    {
        return $value 
            ? asset('storage/' . $value) 
            : asset('frontend/assets/img/Abhishek_profile_pic.webp');
    }

    public function getBackgroundImageAttribute($value)
    {
        return $value 
            ? asset('storage/' . $value) 
            : asset('frontend/assets/videos/AI_brain.mp4');
    }

    // ✅ FIXED typed_items accessor
    public function getTypedItemsAttribute($value)
    {
        $items = $value ? json_decode($value, true) : null;

        if (!$items || !is_array($items)) {
            return [
                'Laravel Developer',
                'API Developer',
                'Web Developer'
            ];
        }

        return $items;
    }
}