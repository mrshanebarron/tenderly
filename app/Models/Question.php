<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'critical' => 'text-red-400 bg-red-400/10',
            'high' => 'text-amber-400 bg-amber-400/10',
            default => 'text-zinc-400 bg-zinc-400/10',
        };
    }
}
