<?php
    use Filament\Notifications\Notification;
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware, name};
    use App\Models\ActivityLog;
    use Wave\Post;
    use Wave\ApiKey;
    
    middleware('auth');
    name('settings.export');

	new class extends Component
	{
        public function exportData()
        {
            $user = auth()->user();
            
            // Gather all user data
            $data = [
                'exported_at' => now()->toDateTimeString(),
                'profile' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'avatar' => $user->avatar(),
                    'verified' => $user->verified,
                    'created_at' => $user->created_at->toDateTimeString(),
                    'updated_at' => $user->updated_at->toDateTimeString(),
                ],
                'profile_fields' => $user->keyValues ? $user->keyValues->map(function ($kv) {
                    return [
                        'key' => $kv->key,
                        'value' => $kv->value,
                    ];
                })->toArray() : [],
                'privacy_settings' => $user->privacy_settings ?? [],
                'notification_preferences' => $user->notification_preferences ?? [],
                'social_links' => $user->social_links ?? [],
                'activity_logs' => $user->activityLogs()->orderBy('created_at', 'desc')->get()->map(function ($log) {
                    return [
                        'action' => $log->action,
                        'description' => $log->description,
                        'ip_address' => $log->ip_address,
                        'metadata' => $log->metadata,
                        'created_at' => $log->created_at->toDateTimeString(),
                    ];
                })->toArray(),
                'api_keys' => $user->apiKeys()->get()->map(function ($key) {
                    return [
                        'name' => $key->name,
                        'key' => substr($key->key, 0, 10) . '...' . substr($key->key, -5), // Partially masked
                        'last_used_at' => $key->last_used_at ? $key->last_used_at->toDateTimeString() : null,
                        'created_at' => $key->created_at->toDateTimeString(),
                    ];
                })->toArray(),
                'blog_posts' => Post::where('author_id', $user->id)->get()->map(function ($post) {
                    return [
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'excerpt' => $post->excerpt,
                        'status' => $post->status,
                        'featured' => $post->featured,
                        'category' => $post->category ? $post->category->name : null,
                        'created_at' => $post->created_at->toDateTimeString(),
                        'updated_at' => $post->updated_at->toDateTimeString(),
                    ];
                })->toArray(),
            ];
            
            // Add subscription data if available
            if ($user->subscription) {
                $subscription = $user->subscription;
                $data['subscription'] = [
                    'plan' => $subscription->plan->name ?? null,
                    'status' => $subscription->status,
                    'cycle' => $subscription->cycle ?? null,
                    'created_at' => $subscription->created_at->toDateTimeString(),
                    'ends_at' => $subscription->ends_at ? $subscription->ends_at->toDateTimeString() : null,
                ];
            } else {
                $data['subscription'] = null;
            }
            
            // Add roles and permissions
            $data['roles'] = $user->roles->pluck('name')->toArray();
            $data['permissions'] = $user->getAllPermissions()->pluck('name')->toArray();
            
            // Log the export
            ActivityLog::log('data_exported', 'User data exported');
            
            // Generate JSON file
            $filename = 'user-data-' . $user->username . '-' . now()->format('Y-m-d-His') . '.json';
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            // Return as download
            return response()->streamDownload(function () use ($json) {
                echo $json;
            }, $filename, [
                'Content-Type' => 'application/json',
            ]);
        }
	}
?>

<x-layouts.app>
    @volt('settings.export') 
        <div class="relative">
            <x-app.settings-layout
                title="Export Data"
                description="Download a copy of all your data stored in our system."
            >
                <div class="w-full max-w-lg space-y-6">
                    <!-- Export Card -->
                    <div class="p-6 bg-white dark:bg-zinc-800 border rounded-lg shadow-sm border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">Download Your Data</h3>
                                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    Export a complete copy of your account data including:
                                </p>
                                <ul class="mt-3 space-y-1 text-sm text-zinc-600 dark:text-zinc-400 list-disc list-inside">
                                    <li>Profile information and settings</li>
                                    <li>Activity logs and account history</li>
                                    <li>Blog posts you've authored</li>
                                    <li>API keys (partially masked)</li>
                                    <li>Privacy and notification preferences</li>
                                    <li>Subscription information</li>
                                    <li>Roles and permissions</li>
                                </ul>
                                <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                                    Your data will be exported in JSON format for easy processing and portability.
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button 
                                wire:click="exportData"
                                type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800"
                            >
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Export My Data
                            </button>
                        </div>
                    </div>

                    <!-- GDPR Info -->
                    <div class="p-4 rounded-md bg-blue-50 dark:bg-blue-900/20">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-400 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Data Privacy</h3>
                                <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                                    This feature complies with GDPR data portability requirements. Your data export will be logged in your activity history for security purposes.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Actions -->
                    <div class="p-4 border rounded-md border-zinc-200 dark:border-zinc-700">
                        <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Need to delete your account?</h4>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                            If you'd like to permanently delete your account and all associated data, visit the 
                            <a href="{{ route('settings.deletion') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">Account Security</a> page.
                        </p>
                    </div>
                </div>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
