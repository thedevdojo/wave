<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserSetting extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'workspace_id',
        'key',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * Get the user that owns the setting.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the workspace that owns the setting.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
    
    /**
     * Get a setting for a user.
     *
     * @param int $userId
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getForUser($userId, $key, $default = null)
    {
        $setting = self::where('user_id', $userId)
            ->where('workspace_id', null)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }
    
    /**
     * Set a setting for a user.
     *
     * @param int $userId
     * @param string $key
     * @param mixed $value
     * @return UserSetting
     */
    public static function setForUser($userId, $key, $value)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'workspace_id' => null,
                'key' => $key,
            ],
            [
                'value' => $value,
            ]
        );
    }
    
    /**
     * Get a setting for a workspace.
     *
     * @param int $workspaceId
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getForWorkspace($workspaceId, $key, $default = null)
    {
        // Ensure workspace_id is cast to integer for foreign key compatibility
        $workspaceId = is_numeric($workspaceId) ? (int)$workspaceId : $workspaceId;
        
        $setting = self::where('workspace_id', $workspaceId)
            ->where('user_id', auth()->id())
            ->where('key', $key)
            ->first();

        return $setting ? $setting->value : $default;
    }
    
    /**
     * Set a setting for a workspace.
     *
     * @param int $workspaceId
     * @param string $key
     * @param mixed $value
     * @return UserSetting
     */
    public static function setForWorkspace($workspaceId, $key, $value)
    {
        // Ensure workspace_id is cast to integer for foreign key compatibility
        $workspaceId = is_numeric($workspaceId) ? (int)$workspaceId : $workspaceId;
        
        try {
            // Try to update or create with the workspace ID
            return self::updateOrCreate(
                [
                    'workspace_id' => $workspaceId,
                    'key' => $key,
                    'user_id' => auth()->id(),
                ],
                [
                    'value' => $value,
                ]
            );
        } catch (\Exception $e) {
            // If there's a unique constraint violation, try a more targeted approach
            Log::warning('Error setting workspace setting, trying fallback approach', [
                'error' => $e->getMessage(),
                'workspace_id' => $workspaceId,
                'key' => $key
            ]);
            
            // Use transaction to ensure atomicity
            return DB::transaction(function() use ($workspaceId, $key, $value) {
                // First delete any existing record with this user_id + key combination
                self::where('user_id', auth()->id())
                    ->where('key', $key)
                    ->delete();
                
                // Now create a new record
                return self::create([
                    'user_id' => auth()->id(),
                    'workspace_id' => $workspaceId,
                    'key' => $key,
                    'value' => $value
                ]);
            });
        }
    }
    
    /**
     * Safely generate a key for a setting that's unique per workspace.
     * This can be used if the database constraints can't be modified.
     *
     * @param string $key The base key name
     * @param int|null $workspaceId The workspace ID or null for default workspace
     * @return string The prefixed key
     */
    public static function makeWorkspaceKey($key, $workspaceId = null)
    {
        if ($workspaceId === null) {
            return 'default_' . $key;
        }
        
        return 'ws_' . (int)$workspaceId . '_' . $key;
    }
}
