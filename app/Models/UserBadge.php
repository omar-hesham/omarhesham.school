<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadge extends Model
{
    protected $fillable = [
        'user_id', 'badge_id', 'times_earned',
        'context', 'first_earned_at', 'last_earned_at',
    ];

    protected $casts = [
        'context'         => 'array',
        'first_earned_at' => 'datetime',
        'last_earned_at'  => 'datetime',
    ];

    public function user(): BelongsTo  { return $this->belongsTo(User::class); }
    public function badge(): BelongsTo { return $this->belongsTo(Badge::class); }
}
