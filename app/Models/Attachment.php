<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = ['task_id', 'file_name', 'file_path', 'file_size'];

    /**
     * Lampiran ini menempel pada suatu Tugas tertentu (Many-to-One)
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}