@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Inspiration Feed</h1>
            
            <div class="flex space-x-2">
                <a href="{{ route('inspiration.interests') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Manage Interests
                </a>
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Main Content -->
            <div class="md:w-3/4">
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex space-x-4 mb-4">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md">All</button>
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Trending</button>
                        <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">For You</button>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        @foreach($userInterests as $interest)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                {{ $interest }}
                            </span>
                        @endforeach
                    </div>
                </div>
                
                <div class="space-y-6">
                    @forelse($trendingTopics as $topic)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h2 class="text-xl font-semibold text-gray-900">{{ $topic['title'] }}</h2>
                                <div class="flex items-center">
                                    @if($topic['source'] === 'twitter')
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.1 10.1 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085a4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    @endif
                                    
                                    @if($topic['trending_score'] > 100)
                                        <span class="ml-2 px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Trending</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="text-gray-700 mb-4">{{ $topic['content'] }}</p>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($topic['created_at'])->diffForHumans() }}
                                </div>
                                
                                <div class="flex space-x-2">
                                    <form action="{{ route('inspiration.generate') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="topic" value="{{ $topic['title'] }}">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Generate Post
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-lg shadow-md p-6 text-center">
                            <p class="text-gray-500">No inspirations found. Try adding more interests!</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Interests</h2>
                    <div class="space-y-2">
                        @foreach($userInterests as $interest)
                            <div class="flex items-center">
                                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                                <span class="text-gray-700">{{ $interest }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('inspiration.interests') }}" class="text-sm text-blue-600 hover:text-blue-800">Manage interests</a>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Trending Categories</h2>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Technology</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Hot</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Business</span>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Rising</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Health</span>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">New</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 