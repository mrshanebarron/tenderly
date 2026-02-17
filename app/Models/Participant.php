<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Participant extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'invited_at' => 'datetime',
            'last_active_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    public static function generateToken(): string
    {
        return Str::random(48);
    }

    public function getCompletionPercentAttribute(): int
    {
        $totalQuestions = $this->tender->criteria()->withCount('questions')->get()->sum('questions_count');
        if ($totalQuestions === 0) return 0;
        $answered = $this->responses()->whereNotNull('answer_text')->where('answer_text', '!=', '')->count();
        return (int) round(($answered / $totalQuestions) * 100);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'invited' => 'text-zinc-400 bg-zinc-400/10 border-zinc-400/20',
            'active' => 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'submitted' => 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20',
            'locked' => 'text-red-400 bg-red-400/10 border-red-400/20',
        };
    }
}
