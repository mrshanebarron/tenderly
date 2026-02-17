<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
{
    protected $guarded = [];

    public function response(): BelongsTo
    {
        return $this->belongsTo(Response::class);
    }
}
