<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UserTracking;
use Illuminate\Support\Facades\Storage;

class SeoSetting extends Model
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
    protected $table = 'seo_settings';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'page_name',

        // ===== BASIC SEO
        'title',
        'description',
        'keywords',
        'canonical',

        // ===== OPEN GRAPH
        'og_title',
        'og_description',
        'og_url',
        'og_type',
        'og_image',

        // ===== TWITTER
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',

        // ===== STATUS
        'status',

        // ===== AUDIT
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | APPENDS (auto include in JSON)
    |--------------------------------------------------------------------------
    */
    protected $appends = [
        'og_image_url',
        'twitter_image_url',
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

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (Fallback values)
    |--------------------------------------------------------------------------
    */

    public function getTitleAttribute($value)
    {
        return $value ?: config('app.name');
    }

    public function getOgTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getTwitterTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    /*
    |--------------------------------------------------------------------------
    | IMAGE ACCESSORS (🔥 IMPORTANT)
    |--------------------------------------------------------------------------
    */

    public function getOgImageUrlAttribute()
    {
        if ($this->og_image && Storage::disk('public')->exists($this->og_image)) {
            return asset('storage/' . $this->og_image);
        }

        return asset('frontend/assets/img/Under_Construction.webp');
    }

    public function getTwitterImageUrlAttribute()
    {
        if ($this->twitter_image && Storage::disk('public')->exists($this->twitter_image)) {
            return asset('storage/' . $this->twitter_image);
        }

        return asset('frontend/assets/img/Under_Construction.webp');
    }
}