@extends('layouts.app')

@section('content')
<div class="py-10 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Manage Your Interests</h1>
            
            <div class="flex space-x-2">
                <a href="{{ route('inspiration.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Inspiration
                </a>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-700 mb-6">Select topics that interest you to personalize your inspiration feed. We'll show you trending content based on your interests.</p>
            
            <form action="{{ route('inspiration.update_interests') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @foreach($allTags as $tag)
                        <div class="flex items-center">
                            <input 
                                id="tag-{{ $tag }}" 
                                name="interests[]" 
                                value="{{ $tag }}" 
                                type="checkbox" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                {{ in_array($tag, $userInterests) ? 'checked' : '' }}
                            >
                            <label for="tag-{{ $tag }}" class="ml-2 block text-sm text-gray-700">
                                {{ $tag }}
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Save Interests
                    </button>
                </div>
            </form>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Interest Categories</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Business & Technology</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Artificial Intelligence
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Blockchain & Cryptocurrency
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Startups & Entrepreneurship
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Remote Work
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Digital Marketing
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Lifestyle & Culture</h3>
                    <ul class="space-y-2 text-gray-700">
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Health & Wellness
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Sustainable Living
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Travel & Adventure
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Food & Cooking
                        </li>
                        <li class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Personal Development
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-yellow-50 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Premium Feature</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>The Inspiration Feed is available for premium subscribers only. Upgrade your plan to access this feature.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 