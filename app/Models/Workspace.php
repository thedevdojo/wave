<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory, SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'logo',
        'user_id',
    ];
    
    protected static function boot()
    {
        parent::boot();
    }
    
    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function members()
    {
        return $this->belongsToMany(User::class, 'workspace_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
    
    public function settings()
    {
        return $this->hasMany(UserSetting::class, 'workspace_id');
    }
    
    public function generatedPosts()
    {
        return $this->hasMany(GeneratedPost::class);
    }
    
    public function invitations()
    {
        return $this->hasMany(WorkspaceInvitation::class);
    }
    
    // Helper methods for workspace settings
    public function getSetting($key, $default = null)
    {
        return UserSetting::getForWorkspace($this->id, $key, $default);
    }
    
    public function setSetting($key, $value)
    {
        return UserSetting::setForWorkspace($this->id, $key, $value);
    }
}
