<?php
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware};
    use App\Models\GeneratedPost;
    
    middleware(['auth']);

    new class extends Component {
        public $posts;
        public $hasPages = false;
        
        public function mount()
        {
            $this->loadPosts();
        }
        
        public function loadPosts()
        {
            $postsCollection = GeneratedPost::where('user_id', auth()->id())
                ->latest()
                ->paginate(20);
                
            $this->posts = $postsCollection->items();
            $this->hasPages = $postsCollection->hasPages();
            
            // Store pagination links in a format that can be used by the view
            $this->paginationLinks = [
                'current_page' => $postsCollection->currentPage(),
                'last_page' => $postsCollection->lastPage(),
                'per_page' => $postsCollection->perPage(),
                'total' => $postsCollection->total(),
            ];
        }
        
        public function copyToClipboard($content)
        {
            $this->dispatch('clipboard-copy', text: $content);
        }
    }
?>

<x-layouts.app>
    @volt('generator-posts-history')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Generated Posts History</h1>
            </div>

            <div class="space-y-4">
                @forelse($posts as $post)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $post->topic }}</h3>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <button wire:click="copyToClipboard('{{ $post->content }}')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Copy
                            </button>
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none mb-4">
                            {{ $post->content }}
                        </div>

                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($post->tone) }}
                            </span>
                            @if($post->is_longform)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Long form
                                </span>
                            @endif
                            @if($post->has_emoji)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Emojis
                                </span>
                            @endif
                            @if($post->has_hashtags)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Hashtags
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No posts generated yet</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Head over to the generator to create your first post!</p>
                        <div class="mt-6">
                            <a href="{{ route('generator') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                                Go to Generator
                            </a>
                        </div>
                    </div>
                @endforelse

                @if($hasPages)
                <div class="mt-6 flex justify-center">
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                        <a href="?page={{ max($paginationLinks['current_page'] - 1, 1) }}" 
                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $paginationLinks['current_page'] <= 1 ? 'cursor-not-allowed opacity-50' : '' }}">
                            Previous
                        </a>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            Page {{ $paginationLinks['current_page'] }} of {{ $paginationLinks['last_page'] }}
                        </span>
                        <a href="?page={{ min($paginationLinks['current_page'] + 1, $paginationLinks['last_page']) }}" 
                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 {{ $paginationLinks['current_page'] >= $paginationLinks['last_page'] ? 'cursor-not-allowed opacity-50' : '' }}">
                            Next
                        </a>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.app>

<script>
    document.addEventListener('clipboard-copy', (e) => {
        const text = e.detail.text;
        navigator.clipboard.writeText(text);
        // Optional: Show a toast notification that text was copied
    });
</script> 