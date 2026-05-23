<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'slug', 'owner_id'];

    /**
     * Relasi ke User sebagai Pemilik Workspace (Many-to-One)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Relasi ke User sebagai Anggota Tim (Many-to-Many via Pivot team_members)
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Relasi ke tabel Tasks (One-to-Many)
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}