<?php

namespace App\Models;

use App\Traits\HasAuditColumns;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ImageMeta;

class ImageOptimizationLog extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $fillable = [
        'image_meta_id',
        'request_id',
        'route_path',
        'full_url',
        'http_method',
        'image_position',
        'image_src',
        'image_alt',
        'image_class',
        'image_id',
        'image_role',
        'status',
        'mode',
        'score',
        'confidence',
        'loading_value',
        'fetchpriority_value',
        'decoding_value',
        'image_width',
        'image_height',
        'reasons',
        'context_payload',
        'user_agent',
        'ip_address',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'reasons' => 'array',
        'context_payload' => 'array',
    ];

    // ✅ RELATION
    public function imageMeta()
    {
        return $this->belongsTo(ImageMeta::class, 'image_meta_id');
    }
}