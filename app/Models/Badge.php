<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    protected $fillable = [
        'key', 'name', 'name_ar', 'description', 'description_ar',
        'emoji', 'icon_color', 'category', 'tier', 'xp_value',
        'is_repeatable', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_repeatable' => 'boolean',
        'is_active'     => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withPivot(['times_earned', 'context', 'first_earned_at', 'last_earned_at'])
            ->withTimestamps();
    }

    // ── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /** Tier colour for UI badge border/glow */
    public function tierColor(): string
    {
        return match ($this->tier) {
            'bronze'   => '#CD7F32',
            'silver'   => '#C0C0C0',
            'gold'     => '#D4AF37',
            'platinum' => '#E5E4E2',
            default    => '#D4AF37',
        };
    }
}
