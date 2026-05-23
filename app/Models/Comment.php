<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = ['task_id', 'user_id', 'content'];

    /**
     * Komentar ini merujuk pada suatu Tugas tertentu (Many-to-One)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Komentar ini ditulis oleh seorang User (Many-to-One)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}