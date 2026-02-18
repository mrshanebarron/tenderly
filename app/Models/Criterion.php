<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    use HasFactory;

    protected $table = 'criteria';
    protected $guarded = [];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Criterion::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Criterion::class, 'parent_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
