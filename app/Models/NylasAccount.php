<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NylasAccount extends Model
{
    use HasFactory;

    protected $table = 'nylas_accounts';

    protected $fillable = [
        'user_id',
        'grant_id',
        'email',
    ];

    /**
     * Get the user that owns the Nylas account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
