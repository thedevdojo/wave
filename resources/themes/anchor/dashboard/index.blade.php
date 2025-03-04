@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Dashboard</h1>
        
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500 truncate">Total Posts</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $totalPosts ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500 truncate">This Month</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $postsThisMonth ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500 truncate">Posted to X</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $postedToX ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-sm font-medium text-gray-500 truncate">Credits Left</p>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ auth()->user()->post_credits ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions & Recent Posts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-1">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    <a href="{{ url('/dashboard/generator') }}" class="flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-md transition duration-150">
                        <svg class="h-6 w-6 text-blue-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="text-blue-700 font-medium">Generate New Post</span>
                    </a>
                    
                    <a href="#" class="flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-md transition duration-150">
                        <svg class="h-6 w-6 text-purple-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-purple-700 font-medium">Content Calendar</span>
                    </a>
                    
                    <a href="#" class="flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-md transition duration-150">
                        <svg class="h-6 w-6 text-green-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-green-700 font-medium">Analytics</span>
                    </a>
                    
                    <a href="#" class="flex items-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-md transition duration-150">
                        <svg class="h-6 w-6 text-yellow-600 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-yellow-700 font-medium">Get More Credits</span>
                    </a>
                </div>
            </div>
            
            <!-- Recent Posts -->
            <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Recent Posts</h2>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                
                <div class="space-y-4" id="recent-posts">
                    <div class="text-center text-gray-500 py-8">
                        Loading recent posts...
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Ideas -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Content Ideas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="border border-gray-200 rounded-md p-4 hover:bg-blue-50 transition duration-150 cursor-pointer">
                    <h3 class="font-medium text-gray-800 mb-2">Industry Trends</h3>
                    <p class="text-gray-600 text-sm">Create a post about the latest trends in your industry to position yourself as a thought leader.</p>
                </div>
                
                <div class="border border-gray-200 rounded-md p-4 hover:bg-blue-50 transition duration-150 cursor-pointer">
                    <h3 class="font-medium text-gray-800 mb-2">Behind the Scenes</h3>
                    <p class="text-gray-600 text-sm">Share a behind-the-scenes look at your work process to connect with your audience.</p>
                </div>
                
                <div class="border border-gray-200 rounded-md p-4 hover:bg-blue-50 transition duration-150 cursor-pointer">
                    <h3 class="font-medium text-gray-800 mb-2">Customer Success Story</h3>
                    <p class="text-gray-600 text-sm">Highlight a customer success story to build trust and showcase your value.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load recent posts
        loadRecentPosts();
        
        function loadRecentPosts() {
            const recentPostsContainer = document.getElementById('recent-posts');
            
            // Get the JWT token from the meta tag
            const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
            
            fetch('/api/extension/history', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Authorization': token ? `Bearer ${token}` : ''
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to load recent posts');
                    });
                }
                return response.json();
            })
            .then(data => {
                recentPostsContainer.innerHTML = '';
                
                if (data.posts && data.posts.length > 0) {
                    // Only show the 5 most recent posts
                    const recentPosts = data.posts.slice(0, 5);
                    
                    recentPosts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.className = 'border-b border-gray-200 pb-4 last:border-b-0 last:pb-0';
                        
                        const postContent = document.createElement('p');
                        postContent.className = 'text-gray-800 mb-2 line-clamp-2';
                        postContent.textContent = post.content;
                        
                        const postMeta = document.createElement('div');
                        postMeta.className = 'flex justify-between text-sm text-gray-500';
                        
                        const postTopic = document.createElement('span');
                        postTopic.textContent = `Topic: ${post.topic}`;
                        
                        const postDate = document.createElement('span');
                        const date = new Date(post.created_at);
                        postDate.textContent = date.toLocaleDateString();
                        
                        postMeta.appendChild(postTopic);
                        postMeta.appendChild(postDate);
                        
                        postElement.appendChild(postContent);
                        postElement.appendChild(postMeta);
                        
                        recentPostsContainer.appendChild(postElement);
                    });
                } else {
                    recentPostsContainer.innerHTML = '<div class="text-center text-gray-500 py-8">No posts found. Generate your first post!</div>';
                }
            })
            .catch(error => {
                console.error('Error loading recent posts:', error);
                recentPostsContainer.innerHTML = `<div class="text-center text-red-500 py-8">Error loading posts: ${error.message}</div>`;
            });
        }
    });
</script>
@endsection
