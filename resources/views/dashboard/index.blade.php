<?php
    use Livewire\Volt\Component;
    use function Laravel\Folio\{middleware};
    
    middleware(['auth']);
    
    new class extends Component {
        public $showExtensionInfo = true;
        
        public function mount(): void
        {
            $this->showExtensionInfo = !session()->has('extension_info_dismissed');
        }
        
        public function dismissExtensionInfo()
        {
            $this->showExtensionInfo = false;
            session()->put('extension_info_dismissed', true);
        }
    }
?>

<x-layouts.app>
    @volt('dashboard')
    <x-app.container class="lg:space-y-6" x-cloak>
        
        <x-app.heading
            title="Dashboard"
            description="Welcome to your social media content hub"
            :border="false"
        />
        
        <div class="flex flex-col">
            <!-- Main Content -->
            <div class="w-full">

                <!-- Browser Extension Promotion - Full Width -->
                @if($showExtensionInfo)
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-5 mb-6 relative">
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
                            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                <a href="#" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Get the extension 
                                    <svg class="ml-1.5 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Quick Actions - Two Equal Columns -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Start a New Article Card -->
                    <a href="/generator" class="group relative flex flex-col p-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Generate new post</h3>
                            <svg class="w-4 h-4 text-gray-900 dark:text-white transform transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Create engaging social media posts with AI assistance. Perfect for X (Twitter) and other platforms.</p>
                    </a>

                    <!-- Find Content Ideas Card -->
                    <a href="{{ route('inspiration.index') }}" class="group relative flex flex-col p-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Get post ideas</h3>
                            <svg class="w-4 h-4 text-gray-900 dark:text-white transform transition-transform group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Discover trending topics and content ideas that resonate with your audience on social media.</p>
                    </a>
                </div>

                <!-- Content Management - Reorganized with Calendar at top and full width -->
                
                <!-- Scheduled Posts / Calendar (Coming Soon) - Full Width -->
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Content calendar</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Coming Soon
                        </span>
                    </div>
                    
                    <div class="text-center py-8 mb-8">
                        <!-- Calendar illustration -->
                        <img 
                            src="{{ asset('images/calendar-img.png') }}" 
                            alt="Calendar illustration"
                            width="150"
                            class="mx-auto mb-4"
                            height="150"
                        />
                        
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-2">Calendar Feature Coming Soon</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                            We're working on a content calendar to help you plan and schedule your social media posts. Stay tuned for this exciting feature!
                        </p>
                    </div>
                </div>

                <!-- Recently Generated Posts - Full Width -->
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recently generated posts</h3>
                        <a href="{{ route('posts.history') }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                            View All
                        </a>
                    </div>
                    
                    @php
                        $recentPosts = \App\Models\GeneratedPost::where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp
                    
                    @if($recentPosts->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-1">
                                                {{ \Illuminate\Support\Str::limit($post->topic, 50) }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                                {{ \Illuminate\Support\Str::limit($post->content, 120) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $post->created_at->format('M d, Y') }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('posts.history') }}#post-{{ $post->id }}" class="text-sm text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                            <button class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300">
                                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                                                </svg>
                                                Post
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No posts yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new post.</p>
                            <div class="mt-6">
                                <a href="/generator" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Create Post
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app>
