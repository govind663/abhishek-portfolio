<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_message',
        'ai_reply',
        'suggestions',
    ];

    // ==== Cast suggestions as array
    protected $casts = [
        'suggestions' => 'array',
    ];

    // ==== Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}