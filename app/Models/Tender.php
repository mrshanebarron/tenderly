<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tender extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(TenderDocument::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analysis::class);
    }

    public function getProgressAttribute(): int
    {
        $participants = $this->participants()->count();
        if ($participants === 0) return 0;
        $submitted = $this->participants()->where('status', 'submitted')->count();
        return (int) round(($submitted / $participants) * 100);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'text-zinc-400 bg-zinc-400/10 border-zinc-400/20',
            'active' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'reviewing' => 'text-amber-400 bg-amber-400/10 border-amber-400/20',
            'completed' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
            'archived' => 'text-zinc-500 bg-zinc-500/10 border-zinc-500/20',
        };
    }
}
