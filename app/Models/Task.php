<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'team_id', 
        'assignee_id', 
        'title', 
        'description', 
        'status', 
        'priority', 
        'deadline'
    ];

    /**
     * Tugas ini milik sebuah Workspace/Team (Many-to-One)
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Tugas ini ditugaskan ke salah satu User pelaksana (Many-to-One)
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Tugas ini bisa memiliki banyak Komentar (One-to-Many)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Tugas ini bisa memiliki banyak Lampiran/Attachment (One-to-Many)
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }
}