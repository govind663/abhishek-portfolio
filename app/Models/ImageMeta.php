<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasAuditColumns;

class ImageMeta extends Model
{
    use SoftDeletes, HasAuditColumns;

    protected $table = 'image_meta';

    protected $fillable = [
        'path',
        'width',
        'height',
        'type',
        'file_size',
        'hash',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'width' => 'integer',
        'height' => 'integer',
        'file_size' => 'integer',
    ];

    // ✅ RELATION
    public function logs()
    {
        return $this->hasMany(ImageOptimizationLog::class, 'image_meta_id');
    }
}