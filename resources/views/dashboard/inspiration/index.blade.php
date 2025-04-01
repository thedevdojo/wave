<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    use App\Models\Inspiration;
    use App\Models\InspirationTag;
    use Illuminate\Support\Facades\Auth;
    
    middleware('auth');

    new class extends Component {
        public $userInterests = [];
        public $trendingTopics = [];
        public $inspirationItems = [];
        public $featuredInspirationItems = [];
        public $categories = [];
        public $activeFilter = 'all';
        public $paginationInfo = [];
        
        public function mount($userInterests = [], $trendingTopics = [], $inspirations = null, $featuredInspirations = null, $categories = [])
        {
            $this->userInterests = $userInterests;
            $this->trendingTopics = $trendingTopics;
            $this->categories = $categories;
            
            // Extract only the data we need from the paginated collection
            if ($inspirations) {
                $this->inspirationItems = $inspirations->items();
                $this->paginationInfo = [
                    'current_page' => $inspirations->currentPage(),
                    'last_page' => $inspirations->lastPage(),
                    'per_page' => $inspirations->perPage(),
                    'total' => $inspirations->total(),
                ];
            }
            
            // Extract featured inspirations data
            if ($featuredInspirations) {
                $this->featuredInspirationItems = $featuredInspirations->all();
            }
        }
        
        public function setFilter($filter)
        {
            $this->activeFilter = $filter;
            
            // Logic to filter the inspirations based on the selected filter
            // This would update the component state without a page reload
        }
        
        public function generatePostFromTopic($topic)
        {
            // Redirect to generator with the selected topic
            return redirect()->route('generator')->with('topic', $topic);
        }
    }
?>

<x-layouts.app>
    @volt('inspiration-feed')
    <x-app.container x-data class="lg:space-y-6" x-cloak>
        
        <x-app.heading
            title="Content ideas"
            description="Discover trending topics and content based on your interests"
            :border="false"
        />
        
        <div class="flex flex-col">
            <!-- Main Content -->
            <div class="w-full">
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-4">
                            <button wire:click="setFilter('all')" class="px-4 py-2 {{ $activeFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-zinc-300' }} rounded-md">All</button>
                            <button wire:click="setFilter('trending')" class="px-4 py-2 {{ $activeFilter === 'trending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-zinc-300' }} rounded-md">Trending</button>
                            <button wire:click="setFilter('for-you')" class="px-4 py-2 {{ $activeFilter === 'for-you' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 dark:bg-zinc-700 dark:text-zinc-300' }} rounded-md">For You</button>
                        </div>
                        <a href="{{ route('inspiration.interests') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-600 dark:hover:bg-zinc-600">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Manage interests
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Your interests:</h3>
                        <div class="flex flex-wrap gap-2">
                            @if(count($userInterests) > 0)
                                @foreach($userInterests as $interest)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $interest }}
                                    </span>
                                @endforeach
                            @else
                                <p class="text-gray-500 dark:text-gray-400">You haven't set any interests yet. <a href="{{ route('inspiration.interests') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Manage your interests</a></p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    @forelse($trendingTopics as $topic)
                        <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $topic['title'] }}</h2>
                                <div class="flex items-center">
                                    @if($topic['source'] === 'twitter')
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.1 10.1 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    @endif
                                    
                                    @if($topic['trending_score'] > 100)
                                        <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs dark:bg-red-900 dark:text-red-200">Trending</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $topic['content'] }}</p>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($topic['created_at'])->diffForHumans() }}
                                </div>
                                
                                <div class="flex space-x-2">
                                    <button 
                                        wire:click="generatePostFromTopic('{{ $topic['title'] }}')"
                                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                    >
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Generate Post
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 text-center">
                            <p class="text-gray-500 dark:text-gray-400">No inspirations found. Try adding more interests!</p>
                        </div>
                    @endforelse

                    @if(count($inspirationItems) > 0)
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-8 mb-4">All Inspirations</h2>
                        @foreach($inspirationItems as $inspiration)
                            <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                                <div class="flex justify-between items-start mb-4">
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $inspiration->title }}</h2>
                                    <div class="flex items-center">
                                        @if($inspiration->is_featured)
                                            <span class="ml-2 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs dark:bg-yellow-900 dark:text-yellow-200">Featured</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ Str::limit($inspiration->content, 150) }}</p>
                                
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($inspiration->tags as $tag)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $inspiration->created_at->diffForHumans() }}
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="generatePostFromTopic('{{ $inspiration->title }}')"
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                        >
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Generate Post
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($paginationInfo['last_page'] > 1)
                            <div class="flex justify-center mt-6">
                                <nav class="flex items-center">
                                    <span class="text-sm text-gray-700 dark:text-gray-400">
                                        Showing <span class="font-semibold">{{ $paginationInfo['current_page'] }}</span> of <span class="font-semibold">{{ $paginationInfo['last_page'] }}</span> pages
                                    </span>
                                </nav>
                            </div>
                        @endif
                    @endif
                </div>
                
                @if(count($featuredInspirationItems) > 0 && $activeFilter !== 'trending')
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mt-8 mb-4">Featured Content</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                        @foreach($featuredInspirationItems as $featured)
                            <div class="p-5 bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $featured->title }}</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-4 text-sm">{{ Str::limit($featured->content, 100) }}</p>
                                <button 
                                    wire:click="generatePostFromTopic('{{ $featured->title }}')"
                                    class="inline-flex items-center px-3 py-1 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                                >
                                    Generate
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app> 