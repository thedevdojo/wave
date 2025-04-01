<?php
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware};
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Textarea;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Checkbox;
    use Filament\Forms\Components\Toggle;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use App\Services\OpenAIService;
    use App\Models\GeneratedPost;
    use App\Models\UserSetting;
    use App\Models\Workspace;
    use Illuminate\Broadcasting\InteractsWithSockets;
    use Illuminate\Broadcasting\PrivateChannel;
    use Illuminate\Foundation\Events\Dispatchable;
    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
    use Illuminate\Broadcasting\Channel;
    use Illuminate\Broadcasting\InteractsWithBroadcasts;
    use Illuminate\Broadcasting\InteractsWithMedia;
    
    middleware(['auth']);
    
    new class extends Component implements HasForms {
        use InteractsWithForms;

        public ?array $data = [];
        public $credits;
        public $result = '';
        public $isGenerating = false;
        public $error = '';
        public $history;
        public $showExtensionInfo = true;
        public $customInstructions = '';
        public $showCustomInstructions = false;
        public $currentWorkspaceId = null;
        public $currentWorkspace = null;
        public $generationMode = 'new'; // new, reply, rewrite
        public $originalContent = ''; // For reply or rewrite modes
        
        // Default AI settings - no UI controls
        private $aiSettings = [
            'temperature' => 0.7,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'max_tokens' => 280
        ];

        public function boot(): void
        {
            $this->form = $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data');
                
            // Listen for workspace changes
            $this->listeners = [
                'workspaceChanged' => 'handleWorkspaceChange'
            ];
        }
        
        protected function getFormSchema()
        {
            $schema = [];
            
            // For all modes: topic field
            if ($this->generationMode === 'new') {
                $schema[] = Textarea::make('topic')
                    ->label('Topic')
                    ->placeholder('What would you like to post about?')
                    ->rows(3)
                    ->autofocus()
                    ->required();
            } elseif ($this->generationMode === 'reply') {
                $schema[] = Textarea::make('original_post')
                    ->label('Original Post')
                    ->placeholder('Paste the post you want to reply to here')
                    ->rows(3)
                    ->autofocus()
                    ->required();
                    
                $schema[] = Textarea::make('topic')
                    ->label('Reply Content')
                    ->placeholder('What would you like to say in your reply?')
                    ->rows(3)
                    ->required();
            } else { // rewrite mode
                $schema[] = Textarea::make('original_post')
                    ->label('Original Post')
                    ->placeholder('Paste the post you want to rewrite here')
                    ->rows(3)
                    ->autofocus()
                    ->required();
                    
                $schema[] = Textarea::make('topic')
                    ->label('Rewrite Instructions')
                    ->placeholder('How would you like to rewrite this post? (e.g., "Make it more formal", "Shorten it to fit in a tweet")')
                    ->rows(2)
                    ->required();
            }
            
            // For all modes: tone field
            $schema[] = Select::make('tone')
                ->label('Tone')
                ->options([
                    'casual' => 'Casual',
                    'formal' => 'Formal',
                    'humorous' => 'Humorous',
                    'professional' => 'Professional',
                ])
                ->default('casual')
                ->required();
            
            // Only show longform toggle for new posts, not replies
            if ($this->generationMode !== 'reply') {
                $schema[] = Toggle::make('longform')
                    ->label('Long format post')
                    ->onIcon('heroicon-s-check')
                    ->onColor('success')
                    ->inline();
            }
            
            // For all modes: emoji and hashtags toggles
            $schema[] = Toggle::make('emoji')
                ->label('Include emojis')
                ->onIcon('heroicon-s-check')
                ->onColor('success')
                ->inline();
                
            $schema[] = Toggle::make('hashtags')
                ->label('Add hashtags')
                ->onIcon('heroicon-s-check')
                ->onColor('success')
                ->inline();
            
            return $schema;
        }

        public function mount(): void
        {
            $this->credits = auth()->user()->post_credits;
            $this->form->fill();
            $this->loadHistory();
            $this->showExtensionInfo = !session()->has('extension_info_dismissed');
            
            // Get current workspace from session
            $this->currentWorkspaceId = session('current_workspace_id');
            
            // Load workspace object if there's a workspace ID
            if ($this->currentWorkspaceId !== null) {
                $this->currentWorkspace = Workspace::find($this->currentWorkspaceId);
            }
            
            // Load the appropriate instructions based on workspace
            $this->loadCustomInstructions();
        }
        
        public function setGenerationMode($mode)
        {
            $this->generationMode = $mode;
            $this->result = '';
            $this->error = '';
            
            // Reset form and recreate with new schema
            $this->form = $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data');
            
            $this->form->fill();
        }

        public function loadCustomInstructions()
        {
            try {
                $this->customInstructions = '';
                $userId = auth()->id();
                $workspaceId = $this->currentWorkspaceId;
                $settingKey = $this->getSettingKeyForWorkspace($workspaceId);
                
                if ($workspaceId === null) {
                    // For default workspace, use the model's getForUser method
                    $this->customInstructions = UserSetting::getForUser($userId, $settingKey, '');
                } else {
                    // For specific workspace, use the model's getForWorkspace method
                    $this->customInstructions = UserSetting::getForWorkspace($workspaceId, $settingKey, '');
                }
            } catch (\Exception $e) {
                logger()->error('Error loading custom instructions', [
                    'error' => $e->getMessage(),
                    'workspace_id' => $this->currentWorkspaceId
                ]);
            }
        }

        public function loadHistory()
        {
            $query = GeneratedPost::where('user_id', auth()->id());
            
            try {
                if ($this->currentWorkspaceId !== null) {
                    // For specific workspaces, filter by workspace_id
                    $query->where('workspace_id', (int)$this->currentWorkspaceId);
                } else {
                    // For default workspace, get posts with NULL workspace_id
                    $query->whereNull('workspace_id');
                }
            } catch (\Exception $e) {
                // If we get an error (like column doesn't exist), just ignore the workspace filter
                logger()->error('Error applying workspace filter to history', ['error' => $e->getMessage()]);
            }
            
            $this->history = $query->latest()->take(5)->get();
        }

        public function generate()
        {
            if ($this->credits <= 0) {
                $this->error = 'You have no credits remaining. Please upgrade your plan to continue generating posts.';
                return;
            }

            $this->error = '';
            $this->isGenerating = true;
            $this->result = '';

            try {
                $data = $this->form->getState();
                
                // Add mode information to the data
                $data['generation_mode'] = $this->generationMode;
                
                // Add original post for reply or rewrite modes
                if ($this->generationMode !== 'new' && isset($data['original_post'])) {
                    $data['original_content'] = $data['original_post'];
                }
                
                // Add AI settings to the data
                $data['fine_tuning'] = $this->aiSettings;
                
                $openAI = app(OpenAIService::class);
                $this->result = $openAI->generatePost($data, $this->customInstructions);

                // Save to history
                $postData = [
                    'user_id' => auth()->id(),
                    'content' => $this->result,
                    'topic' => $data['topic'] ?? ($data['original_post'] ?? ''),
                    'tone' => $data['tone'],
                    'is_longform' => $data['longform'] ?? false,
                    'has_emoji' => $data['emoji'] ?? false,
                    'has_hashtags' => $data['hashtags'] ?? false,
                    'settings' => [
                        'custom_instructions' => $this->customInstructions,
                        'generation_mode' => $this->generationMode,
                        'original_content' => $data['original_post'] ?? null
                    ]
                ];
                
                // Try to add workspace_id, but don't fail if the column doesn't exist
                try {
                    if ($this->currentWorkspaceId !== null) {
                        $postData['workspace_id'] = $this->currentWorkspaceId;
                    }
                } catch (\Exception $e) {
                    // Ignore if workspace_id column doesn't exist
                    logger()->error('Failed to set workspace_id on generated post', ['error' => $e->getMessage()]);
                }
                
                GeneratedPost::create($postData);

                // Deduct credit
                $user = auth()->user();
                $user->post_credits -= 1;
                $user->save();
                
                $this->credits = $user->post_credits;
                $this->loadHistory();
            } catch (\Exception $e) {
                $this->error = 'Failed to generate post. Please try again.';
                logger()->error('Post generation failed', ['error' => $e->getMessage()]);
            } finally {
                $this->isGenerating = false;
            }
        }

        public function copyToClipboard()
        {
            $this->dispatch('clipboard-copy', text: $this->result);
        }

        public function copyHistoryItem($content)
        {
            $this->dispatch('clipboard-copy', text: $content);
        }

        public function dismissExtensionInfo()
        {
            $this->showExtensionInfo = false;
            session()->put('extension_info_dismissed', true);
        }
        
        public function toggleCustomInstructions()
        {
            $this->showCustomInstructions = !$this->showCustomInstructions;
        }
        
        public function saveCustomInstructions()
        {
            try {
                $userId = auth()->id();
                $workspaceId = $this->currentWorkspaceId;
                $settingKey = $this->getSettingKeyForWorkspace($workspaceId);
                
                if ($workspaceId === null) {
                    // For default workspace
                    UserSetting::setForUser($userId, $settingKey, $this->customInstructions);
                    $message = 'Default workspace guidelines saved successfully!';
                } else {
                    // For specific workspace
                    UserSetting::setForWorkspace($workspaceId, $settingKey, $this->customInstructions);
                    $message = 'Workspace guidelines saved successfully!';
                }
                
                $this->dispatch('saved', ['message' => $message]);
            } catch (\Exception $e) {
                logger()->error('Error saving custom instructions', [
                    'error' => $e->getMessage(),
                    'workspace_id' => $this->currentWorkspaceId
                ]);
                
                $this->dispatch('saved', ['message' => 'Error saving settings. Please try again.']);
            }
        }
        
        /**
         * Get a consistent key name for workspace settings
         */
        private function getSettingKeyForWorkspace($workspaceId)
        {
            return $workspaceId === null 
                ? 'custom_instructions_default'
                : 'custom_instructions_ws_' . (int)$workspaceId;
        }

        public function handleWorkspaceChange($workspaceId)
        {
            // If $workspaceId is a string but contains a number, cast it to integer
            if (is_string($workspaceId) && is_numeric($workspaceId)) {
                $workspaceId = (int)$workspaceId;
            }
            
            $this->currentWorkspaceId = $workspaceId;
            
            if ($workspaceId !== null) {
                $this->currentWorkspace = Workspace::find($workspaceId);
            } else {
                $this->currentWorkspace = null;
            }
            
            // Reload the custom instructions for this workspace
            $this->loadCustomInstructions();
            $this->loadHistory();
            
            // Force re-render to show the updated instructions
            $this->dispatch('workspace-instructions-updated');
        }
    }
?>

<x-layouts.app>
    @volt('generator')
    <x-app.container x-data class="lg:space-y-6" x-cloak>
        
        <x-app.heading
            title="Content Generator"
            description="Create and manage your social media content with AI assistance"
            :border="false"
        />
        
        <!-- Notification for saved settings -->
        <div
            x-data="{ show: false, message: '' }"
            x-on:saved.window="show = true; message = $event.detail.message || 'Settings saved successfully!'; setTimeout(() => { show = false }, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded shadow-md z-50"
            style="display: none;"
        >
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-text="message"></span>
            </div>
        </div>
        
        <div class="flex flex-col">
            <!-- Main Content -->
            <div class="w-full">
                <!-- Chrome Extension Info Banner -->
                @if($showExtensionInfo)
                    <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-5 mb-6 relative" x-data>
                        <button 
                            wire:click="dismissExtensionInfo" 
                            class="absolute top-0 right-0 p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-800/50 rounded-tr-lg"
                            aria-label="Dismiss"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_3750_2960)">
                                        <path d="M23.9973 11.9993L44.7786 11.9993C42.6727 8.35067 39.6434 5.3208 35.9952 3.21431C32.347 1.10783 28.2084 -0.00101313 23.9957 -0.000718732C19.783 -0.000424334 15.6447 1.109 11.9967 3.21599C8.34881 5.32299 5.3199 8.35328 3.21459 12.0022L13.6052 29.9993L13.6145 29.9969C12.5575 28.1741 11.9997 26.1049 11.9973 23.9979C11.9949 21.8909 12.5481 19.8204 13.601 17.9953C14.6539 16.1702 16.1693 14.655 17.9946 13.6023C19.8198 12.5496 21.8903 11.9967 23.9973 11.9993V11.9993Z" fill="url(#paint0_linear_3750_2960)"/>
                                        <path d="M34.3948 29.9977L24.0041 47.9947C28.2168 47.9953 32.3555 46.8868 36.0038 44.7806C39.6522 42.6744 42.6818 39.6447 44.7879 35.9963C46.894 32.3478 48.0024 28.2092 48.0016 23.9965C48.0009 19.7838 46.891 15.6455 44.7836 11.9978L24.0024 11.9979L23.9998 12.0071C26.1069 12.003 28.1777 12.5546 30.0037 13.606C31.8296 14.6575 33.3461 16.1717 34.4002 17.9961C35.4543 19.8205 36.0089 21.8906 36.0079 23.9976C36.007 26.1047 35.4506 28.1742 34.3948 29.9977V29.9977Z" fill="url(#paint1_linear_3750_2960)"/>
                                        <path d="M13.6089 30.0031L3.21827 12.006C1.1114 15.654 0.00211014 19.7924 0.00195314 24.0051C0.00179615 28.2178 1.11077 32.3563 3.21738 36.0044C5.32398 39.6526 8.35396 42.6818 12.0026 44.7875C15.6513 46.8932 19.7901 48.0012 24.0028 48L34.3934 30.0029L34.3867 29.9961C33.3366 31.8228 31.8236 33.3405 30 34.3961C28.1765 35.4516 26.1068 36.0078 23.9998 36.0085C21.8928 36.0092 19.8228 35.4544 17.9985 34.4001C16.1742 33.3458 14.6601 31.8291 13.6089 30.0031V30.0031Z" fill="url(#paint2_linear_3750_2960)"/>
                                        <path d="M24 36C30.6274 36 36 30.6274 36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36Z" fill="white"/>
                                        <path d="M24 33.5C29.2467 33.5 33.5 29.2467 33.5 24C33.5 18.7533 29.2467 14.5 24 14.5C18.7533 14.5 14.5 18.7533 14.5 24C14.5 29.2467 18.7533 33.5 24 33.5Z" fill="#1A73E8"/>
                                    </g>
                                    <defs>
                                        <linearGradient id="paint0_linear_3750_2960" x1="42.186" y1="10.5017" x2="21.4041" y2="46.4971" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#D93025"/>
                                            <stop offset="1" stop-color="#EA4335"/>
                                        </linearGradient>
                                        <linearGradient id="paint1_linear_3750_2960" x1="46.146" y1="14.9994" x2="4.58208" y2="14.9993" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#FCC934"/>
                                            <stop offset="1" stop-color="#FBBC04"/>
                                        </linearGradient>
                                        <linearGradient id="paint2_linear_3750_2960" x1="26.5984" y1="46.5015" x2="5.81637" y2="10.506" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#1E8E3E"/>
                                            <stop offset="1" stop-color="#34A853"/>
                                        </linearGradient>
                                        <clipPath id="clip0_3750_2960">
                                            <rect width="48" height="48" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">Chrome extension available!</h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                                    <p>Speed up your workflow with our Chrome extension. Quickly reply to posts and create new content directly on X (Twitter) without leaving the platform.</p>
                                    <a href="#" class="mt-2 inline-block text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-200 font-medium">
                                        Get the extension 
                                        <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Custom Instructions Collapsible Section -->
                <div class="mb-6">
                    <button 
                        wire:click="toggleCustomInstructions"
                        type="button" 
                        class="flex w-full justify-between items-center p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800/70 transition-colors"
                    >
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                @if($currentWorkspaceId !== null && $currentWorkspace)
                                    <strong>{{ $currentWorkspace->name }}</strong> Custom instructions
                                @else
                                    Custom instructions & fine-tuning
                                @endif
                            </span>
                        </div>
                        <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 transform transition-transform duration-200" 
                            :class="{ 'rotate-180': @js($showCustomInstructions) }"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div 
                        class="mt-2 p-4 bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 overflow-hidden"
                        style="{{ $showCustomInstructions ? '' : 'display: none;' }}"
                    >
                        <label for="customInstructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            @if($currentWorkspaceId !== null && $currentWorkspace)
                                {{ $currentWorkspace->name }} - Brand & Style Guidelines
                            @else
                                Default Workspace - Brand & Style Guidelines
                            @endif
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                            @if($currentWorkspaceId !== null && $currentWorkspace)
                                These guidelines are specific to the <strong>{{ $currentWorkspace->name }}</strong> workspace and will only apply to posts generated in this workspace.
                            @else
                                These are your default workspace guidelines that will be used when working in the default workspace.
                            @endif
                        </p>
                        <textarea
                            wire:model.lazy="customInstructions"
                            id="customInstructions"
                            rows="4"
                            placeholder="@if($currentWorkspaceId !== null && $currentWorkspace) e.g., {{ $currentWorkspace->name }}'s brand voice is friendly and professional. We avoid using slang or technical jargon. Our target audience is small business owners aged 30-50. @else e.g., Default workspace brand voice is friendly and professional. We avoid using slang or technical jargon. Our target audience is small business owners aged 30-50. @endif"
                            class="w-full rounded-md @if($currentWorkspaceId !== null && $currentWorkspace) border-indigo-300 dark:border-indigo-700 focus:border-indigo-500 focus:ring-indigo-500 @else border-gray-300 dark:border-gray-700 focus:border-blue-500 focus:ring-blue-500 @endif dark:bg-gray-900 shadow-sm"
                        ></textarea>
                        
                        <div class="mt-3 flex justify-end">
                            <button 
                                wire:click="saveCustomInstructions"
                                type="button"
                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Save instructions
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Credits Display and Generator Form -->
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            @if($generationMode === 'new')
                                Create new post
                            @elseif($generationMode === 'reply')
                                Reply to a post
                            @else
                                Rewrite a post
                            @endif
                        </h2>
                        <div class="flex items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400 mr-2">Credits remaining:</span>
                            @if($credits == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $credits }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $credits }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Generation Mode Pills -->
                    <div class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-full mb-5">
                        <button 
                            wire:click="setGenerationMode('new')" 
                            type="button"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-colors {{ $generationMode === 'new' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}"
                        >
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                New Post
                            </div>
                        </button>
                        
                        <button 
                            wire:click="setGenerationMode('reply')" 
                            type="button"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-colors {{ $generationMode === 'reply' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}"
                        >
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                                Reply
                            </div>
                        </button>
                        
                        <button 
                            wire:click="setGenerationMode('rewrite')" 
                            type="button"
                            class="px-4 py-2 text-sm font-medium rounded-full transition-colors {{ $generationMode === 'rewrite' ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-700 dark:text-white' : 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white' }}"
                        >
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                                Rewrite
                            </div>
                        </button>
                    </div>
                    
                    @if($error)
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg">
                            {{ $error }}
                        </div>
                    @endif
                    
                    <form wire:submit="generate">
                        <div class="space-y-4">
                            {{ $this->form }}
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" wire:loading.attr="disabled">
                                    <svg wire:loading.remove wire:target="generate" class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <svg wire:loading wire:target="generate" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="generate">
                                        @if($generationMode === 'new')
                                            Generate post
                                        @elseif($generationMode === 'reply')
                                            Generate reply
                                        @else
                                            Rewrite post
                                        @endif
                                    </span>
                                    <span wire:loading wire:target="generate">Generating...</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Generated Result -->
                @if($result)
                    <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                @if($generationMode === 'new')
                                    Preview
                                @elseif($generationMode === 'reply')
                                    Reply Preview
                                @else
                                    Rewritten Post
                                @endif
                            </h3>
                            <button wire:click="copyToClipboard" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-800 focus:outline-none">
                                <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                </svg>
                                Copy to clipboard
                            </button>
                        </div>
                        
                        <!-- Social Media Post Preview -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 mb-2 max-w-lg mx-auto">
                            <!-- Original Post (for Reply mode only) -->
                            @if($generationMode === 'reply' && isset($data['original_post']))
                            <div class="mb-4 border-b border-gray-100 dark:border-gray-800 pb-4">
                                <div class="flex items-start mb-3">
                                    <!-- Profile Picture for Original Author -->
                                    <div class="flex-shrink-0 mr-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold">
                                            O
                                        </div>
                                    </div>
                                    
                                    <!-- Original Post Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <p class="font-bold text-gray-900 dark:text-white truncate">
                                                Original Author
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                            @originaluser
                                        </p>
                                        <div class="mt-2 text-gray-800 dark:text-gray-200 text-base whitespace-pre-wrap">
                                            {{ $data['original_post'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Post Header -->
                            <div class="flex items-start mb-3">
                                <!-- Profile Picture -->
                                <div class="flex-shrink-0 mr-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                                    </div>
                                </div>
                                
                                <!-- User Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center">
                                        <p class="font-bold text-gray-900 dark:text-white truncate">
                                            {{ auth()->user()->name ?? 'Your Name' }}
                                        </p>
                                        <svg class="ml-1 h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                        @{{ str_replace(' ', '', strtolower(auth()->user()->name ?? 'yourhandle')) }}
                                    </p>
                                </div>
                                
                                <!-- Twitter Logo -->
                                <div class="flex-shrink-0 ml-2">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M23.643 4.937c-.835.37-1.732.62-2.675.733.962-.576 1.7-1.49 2.048-2.578-.9.534-1.897.922-2.958 1.13-.85-.904-2.06-1.47-3.4-1.47-2.572 0-4.658 2.086-4.658 4.66 0 .364.042.718.12 1.06-3.873-.195-7.304-2.05-9.602-4.868-.4.69-.63 1.49-.63 2.342 0 1.616.823 3.043 2.072 3.878-.764-.025-1.482-.234-2.11-.583v.06c0 2.257 1.605 4.14 3.737 4.568-.392.106-.803.162-1.227.162-.3 0-.593-.028-.877-.082.593 1.85 2.313 2.313 4.352 3.234-1.595 1.25-3.604 1.995-5.786 1.995-.376 0-.747-.022-1.112-.065 2.062 1.323 4.51 2.093 7.14 2.093 8.57 0 13.255-7.098 13.255-13.254 0-.2-.005-.402-.014-.602.91-.658 1.7-1.477 2.323-2.41z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Post Content -->
                            <div class="text-gray-800 dark:text-gray-200 text-base mb-2 whitespace-pre-wrap">
                                {{ $result }}
                            </div>
                            
                            <!-- Post Timestamp -->
                            <div class="text-gray-500 dark:text-gray-400 text-sm border-t border-gray-100 dark:border-gray-700 pt-2 mt-2">
                                {{ now()->format('g:i A · M d, Y') }}
                            </div>
                            
                            <!-- Post Actions -->
                            <div class="flex justify-between mt-3 text-gray-500 dark:text-gray-400">
                                <button class="hover:text-blue-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </button>
                                <button class="hover:text-green-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </button>
                                <button class="hover:text-red-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <button class="hover:text-blue-500">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Raw Content (Hidden on Mobile) -->
                        <div class="mt-4 lg:block hidden">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Raw Content:</h4>
                            <div class="prose dark:prose-invert max-w-none p-3 bg-gray-50 dark:bg-gray-900 rounded-md text-sm">
                                {{ $result }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Clipboard copy functionality -->
        <script>
            document.addEventListener('clipboard-copy', event => {
                const el = document.createElement('textarea');
                el.value = event.detail.text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                
                // Show toast or notification
                // This is where you would add a toast notification
            });
        </script>
    </x-app.container>
    @endvolt
</x-layouts.app>
