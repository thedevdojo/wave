@extends('wave::layouts.app')

@php
use Tymon\JWTAuth\Facades\JWTAuth;
@endphp

@section('content')
<meta name="api-token" content="{{ JWTAuth::fromUser(auth()->user()) }}">

<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6">AI Post Generarrtor</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Generate a New Post</h2>
            <div class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                <span id="credits-count">{{ $credits }}</span> credits remaining
            </div>
        </div>
        
        <form id="generator-form" class="space-y-4">
            @csrf
            <div>
                <label for="topic" class="block text-sm font-medium text-gray-700 mb-1">Topic</label>
                <input type="text" id="topic" name="topic" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="What would you like to post about?">
            </div>
            
            <div>
                <label for="tone" class="block text-sm font-medium text-gray-700 mb-1">Tone</label>
                <select id="tone" name="tone" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="casual">Casual</option>
                    <option value="formal">Formal</option>
                    <option value="humorous">Humorous</option>
                    <option value="professional">Professional</option>
                </select>
            </div>
            
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center">
                    <input type="checkbox" id="longform" name="longform" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="longform" class="ml-2 block text-sm text-gray-700">Longer post</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="emoji" name="emoji" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="emoji" class="ml-2 block text-sm text-gray-700">Include emojis</label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="hashtags" name="hashtags" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="hashtags" class="ml-2 block text-sm text-gray-700">Add hashtags</label>
                </div>
            </div>
            
            <div>
                <button type="submit" id="generate-btn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Generate Post
                </button>
            </div>
        </form>
    </div>
    
    <div id="result-container" class="bg-white rounded-lg shadow-md p-6 mb-6 hidden">
        <h2 class="text-xl font-semibold mb-4">Generated Post</h2>
        
        <div id="post-content" class="bg-gray-50 p-4 rounded-md mb-4 whitespace-pre-wrap"></div>
        
        <div class="flex space-x-2">
            <button id="copy-btn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                Copy
            </button>
            <button id="post-to-x-btn" class="bg-black hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                Post to X
            </button>
        </div>
    </div>
    
    <div id="history-container" class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Posts</h2>
        
        <div id="postsHistory" class="space-y-4">
            <div class="text-center text-gray-500 py-8">
                Your generated posts will appear here
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('generator-form');
        const generateBtn = document.getElementById('generate-btn');
        const resultContainer = document.getElementById('result-container');
        const postContent = document.getElementById('post-content');
        const copyBtn = document.getElementById('copy-btn');
        const postToXBtn = document.getElementById('post-to-x-btn');
        const creditsCount = document.getElementById('credits-count');
        const postsHistory = document.getElementById('postsHistory');
        
        // Load post history
        loadPostHistory();
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const topic = document.getElementById('topic').value;
            const tone = document.getElementById('tone').value;
            const longform = document.getElementById('longform').checked;
            const emoji = document.getElementById('emoji').checked;
            const hashtags = document.getElementById('hashtags').checked;
            
            if (!topic) {
                alert('Please enter a topic');
                return;
            }
            
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...';
            
            // Get the JWT token from the meta tag
            const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
            
            fetch('/generator/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Authorization': token ? `Bearer ${token}` : ''
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    topic: topic,
                    tone: tone,
                    longform: longform,
                    emoji: emoji,
                    hashtags: hashtags
                })
            })
            .then(response => response.json())
            .then(data => {
                generateBtn.disabled = false;
                generateBtn.innerHTML = 'Generate Post';
                
                if (data.error) {
                    alert(data.message || data.error);
                    return;
                }
                
                // Update credits count
                creditsCount.textContent = data.remaining_credits;
                
                // Show result
                postContent.textContent = data.post;
                resultContainer.classList.remove('hidden');
                
                // Scroll to result
                resultContainer.scrollIntoView({ behavior: 'smooth' });
                
                // Automatically save the post
                saveGeneratedPost(data.post, data.topic);
            })
            .catch(error => {
                generateBtn.disabled = false;
                generateBtn.innerHTML = 'Generate Post';
                alert('An error occurred. Please try again.');
                console.error('Error:', error);
            });
        });
        
        copyBtn.addEventListener('click', function() {
            navigator.clipboard.writeText(postContent.textContent)
                .then(() => {
                    const originalText = copyBtn.textContent;
                    copyBtn.textContent = 'Copied!';
                    setTimeout(() => {
                        copyBtn.textContent = originalText;
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                });
        });
        
        postToXBtn.addEventListener('click', function() {
            const post = encodeURIComponent(postContent.textContent);
            window.open(`https://twitter.com/intent/tweet?text=${post}`, '_blank');
        });
        
        function loadPostHistory() {
            postsHistory.innerHTML = '<p class="text-center text-gray-500">Loading posts...</p>';
            
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
                        throw new Error(data.message || 'Failed to load post history');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.posts && data.posts.length > 0) {
                    postsHistory.innerHTML = '';
                    
                    data.posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.className = 'border border-gray-200 rounded-md p-4';
                        
                        const postContent = document.createElement('p');
                        postContent.className = 'mb-2';
                        postContent.textContent = post.content;
                        
                        const postMeta = document.createElement('div');
                        postMeta.className = 'flex justify-between text-sm text-gray-500';
                        
                        const postTopic = document.createElement('span');
                        postTopic.textContent = `Topic: ${post.topic}`;
                        
                        const postDate = document.createElement('span');
                        postDate.textContent = new Date(post.created_at).toLocaleDateString();
                        
                        postMeta.appendChild(postTopic);
                        postMeta.appendChild(postDate);
                        
                        postElement.appendChild(postContent);
                        postElement.appendChild(postMeta);
                        
                        postsHistory.appendChild(postElement);
                    });
                } else {
                    postsHistory.innerHTML = '<div class="text-center text-gray-500 py-8">No posts found</div>';
                }
            })
            .catch(error => {
                console.error('Error loading post history:', error);
                postsHistory.innerHTML = '<div class="text-center text-gray-500 py-8">Failed to load post history</div>';
            });
        }
        
        // Function to automatically save the generated post
        function saveGeneratedPost(content, topic) {
            if (!content) {
                console.error('No content to save');
                return;
            }
            
            // Get the JWT token from the meta tag
            const token = document.querySelector('meta[name="api-token"]')?.getAttribute('content');
            
            fetch('/api/extension/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Authorization': token ? `Bearer ${token}` : ''
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    content: content,
                    topic: topic
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to save post');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    console.log('Post saved automatically');
                    // Refresh post history
                    loadPostHistory();
                } else {
                    throw new Error(data.message || 'Failed to save post');
                }
            })
            .catch(error => {
                console.error('Error saving post:', error);
                // Don't show alert to user since this is automatic
            });
        }
    });
</script>
@endsection 