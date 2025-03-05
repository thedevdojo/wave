<?php
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware};
    use Filament\Forms\Components\TextInput;
    use Filament\Forms\Components\Select;
    use Filament\Forms\Components\Checkbox;
    use Filament\Forms\Concerns\InteractsWithForms;
    use Filament\Forms\Contracts\HasForms;
    use Filament\Forms\Form;
    use App\Services\OpenAIService;
    use App\Models\GeneratedPost;

    middleware(['auth']);

    new class extends Component implements HasForms {
        use InteractsWithForms;

        public ?array $data = [];
        public $credits;
        public $result = '';
        public $isGenerating = false;
        public $error = '';
        public $history;

        public function boot(): void
        {
            $this->form = $this->makeForm()
                ->schema([
                    TextInput::make('topic')
                        ->label('Topic')
                        ->placeholder('What would you like to post about?')
                        ->required(),
                    
                    Select::make('tone')
                        ->label('Tone')
                        ->options([
                            'casual' => 'Casual',
                            'formal' => 'Formal',
                            'humorous' => 'Humorous',
                            'professional' => 'Professional',
                        ])
                        ->default('casual')
                        ->required(),
                    
                    Checkbox::make('longform')
                        ->label('Longer post'),
                    
                    Checkbox::make('emoji')
                        ->label('Include emojis'),
                    
                    Checkbox::make('hashtags')
                        ->label('Add hashtags'),
                ])
                ->statePath('data');
        }

        public function mount(): void
        {
            $this->credits = auth()->user()->post_credits;
            $this->form->fill();
            $this->loadHistory();
        }

        public function loadHistory()
        {
            $this->history = GeneratedPost::where('user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get();
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
                
                $openAI = app(OpenAIService::class);
                $this->result = $openAI->generatePost($data);

                // Save to history
                GeneratedPost::create([
                    'user_id' => auth()->id(),
                    'content' => $this->result,
                    'topic' => $data['topic'],
                    'tone' => $data['tone'],
                    'is_longform' => $data['longform'] ?? false,
                    'has_emoji' => $data['emoji'] ?? false,
                    'has_hashtags' => $data['hashtags'] ?? false,
                ]);

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
    }
?>

<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            @volt('generator')
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Generator</h1>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Credits remaining:</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $credits }}</span>
                </div>
            </div>
            
                    @if($error)
                        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/50 text-red-600 dark:text-red-400 rounded-lg">
                            {{ $error }}
                        </div>
                    @endif

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <form wire:submit="generate">
                            {{ $this->form }}
                            
                            <div class="mt-6">
                                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="generate">Generate Post</span>
                                    <span wire:loading wire:target="generate">Generating...</span>
                </button>
            </div>
        </form>
    </div>
    
                    @if($result)
                        <div class="mt-8">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Generated Post</h3>
                                    <button wire:click="copyToClipboard" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Copy to clipboard
                                    </button>
                                </div>
                                <div class="prose dark:prose-invert max-w-none">
                                    {{ $result }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($history && $history->count() > 0)
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Recent Posts</h2>
                                <a href="{{ route('posts.history') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    View all
                                </a>
                            </div>
                            <div class="space-y-4">
                                @foreach($history as $post)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $post->topic }}</span>
                                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                            </div>
                                            <button wire:click="copyHistoryItem('{{ $post->content }}')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                Copy
            </button>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-300 line-clamp-3">{{ $post->content }}</p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                {{ ucfirst($post->tone) }}
                                            </span>
                                            @if($post->is_longform)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Long form
                                                </span>
                                            @endif
                                            @if($post->has_emoji)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Emojis
                                                </span>
                                            @endif
                                            @if($post->has_hashtags)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Hashtags
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
        </div>
    </div>
                    @endif
            </div>
            @endvolt
        </div>
    </div>

<script>
        document.addEventListener('clipboard-copy', (e) => {
            const text = e.detail.text;
            navigator.clipboard.writeText(text).then(() => {
                // Optional: Show a toast notification that text was copied
            });
    });
</script>
</x-layouts.app>
