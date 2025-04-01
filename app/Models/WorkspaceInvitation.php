<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkspaceInvitation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'workspace_id',
        'email',
        'role',
        'token',
        'accepted_at',
        'expires_at',
    ];
    
    protected $casts = [
        'accepted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($invitation) {
            // Generate a token if not provided
            if (!$invitation->token) {
                $invitation->token = Str::random(64);
            }
            
            // Set expiration date if not provided
            if (!$invitation->expires_at) {
                $invitation->expires_at = now()->addDays(7);
            }
        });
    }
    
    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    
    // Helper methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
    
    public function isAccepted()
    {
        return $this->accepted_at !== null;
    }
    
    public function accept(User $user)
    {
        if ($this->isExpired() || $this->isAccepted()) {
            return false;
        }
        
        // Add user to workspace
        $this->workspace->users()->attach($user->id, [
            'role' => $this->role,
        ]);
        
        // Mark invitation as accepted
        $this->accepted_at = now();
        $this->save();
        
        return true;
    }
    
    public function scopePending($query)
    {
        return $query->whereNull('accepted_at')
                     ->where('expires_at', '>', now());
    }
}
