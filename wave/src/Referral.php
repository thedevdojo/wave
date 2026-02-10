<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Referral extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'referred_user_id',
        'code',
        'clicks',
        'conversions',
        'status',
        'converted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clicks' => 'integer',
            'conversions' => 'integer',
            'converted_at' => 'datetime',
        ];
    }

    /**
     * The user who owns the referral code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('wave.user_model', User::class));
    }

    /**
     * The user who was referred.
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(config('wave.user_model', User::class), 'referred_user_id');
    }

    /**
     * The rewards associated with this referral.
     */
    public function rewards(): HasMany
    {
        return $this->hasMany(ReferralReward::class);
    }

    /**
     * Generate a unique referral code.
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Increment the click count.
     */
    public function incrementClicks(): void
    {
        $this->increment('clicks');
    }

    /**
     * Mark as converted and increment conversion count.
     */
    public function markAsConverted(int $referredUserId): void
    {
        $this->update([
            'referred_user_id' => $referredUserId,
            'conversions' => $this->conversions + 1,
            'converted_at' => now(),
        ]);
    }

    /**
     * Check if referral is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get total earnings from this referral.
     */
    public function totalEarnings(): float
    {
        return $this->rewards()->sum('amount');
    }

    /**
     * Get pending earnings.
     */
    public function pendingEarnings(): float
    {
        return $this->rewards()->where('status', 'pending')->sum('amount');
    }

    /**
     * Get paid earnings.
     */
    public function paidEarnings(): float
    {
        return $this->rewards()->where('status', 'paid')->sum('amount');
    }
}
