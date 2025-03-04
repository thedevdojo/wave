<?php
    use function Laravel\Folio\{middleware};
    middleware('auth');
?>

<x-layouts.app>
    <x-app.container x-data class="lg:space-y-6" x-cloak>

        <div class="py-10 bg-gray-50 dark:bg-zinc-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">AI Post Generator</h1>
                </div>
                
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Generator Form -->
                    <div class="md:w-2/3">
                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                            <form id="generator-form" action="{{ route('generator.generate') }}" method="POST">
                                @csrf
                                
                                <div class="mb-6">
                                    <label for="topic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Topic or Keyword</label>
                                    <input type="text" name="topic" id="topic" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" placeholder="Enter a topic or keyword" required>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Platform</label>
                                    <select name="platform" id="platform" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                        <option value="twitter">Twitter</option>
                                        <option value="instagram">Instagram</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="linkedin">LinkedIn</option>
                                    </select>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="tone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tone</label>
                                    <select name="tone" id="tone" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                        <option value="professional">Professional</option>
                                        <option value="casual">Casual</option>
                                        <option value="humorous">Humorous</option>
                                        <option value="inspirational">Inspirational</option>
                                        <option value="educational">Educational</option>
                                    </select>
                                </div>
                                
                                <div class="mb-6">
                                    <label for="additional_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Information (Optional)</label>
                                    <textarea name="additional_info" id="additional_info" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" placeholder="Add any specific details or requirements"></textarea>
                                </div>
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Generate Post
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Generated Result -->
                        <div id="result-container" class="mt-6 bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6 hidden">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Generated Post</h2>
                            <div id="generated-content" class="prose dark:prose-invert max-w-none"></div>
                            
                            <div class="mt-6 flex justify-between">
                                <button id="regenerate-btn" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-zinc-700 dark:text-zinc-300 dark:border-zinc-600 dark:hover:bg-zinc-600">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-zinc-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Regenerate
                                </button>
                                
                                <button id="copy-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    Copy to Clipboard
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="md:w-1/3">
                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Posts</h2>
                            
                            <div class="space-y-4">
                                <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">How to improve your productivity with these 5 simple tips #productivity #worklife</p>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Twitter • 2 hours ago</span>
                                        <button class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Reuse</button>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-gray-50 dark:bg-zinc-700 rounded-md">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">Excited to announce our new product launch! Check out our website for more details. #newproduct #innovation</p>
                                    <div class="mt-2 flex justify-between items-center">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">LinkedIn • 1 day ago</span>
                                        <button class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Reuse</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('posts.history') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View all generated posts</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </x-app.container>
</x-layouts.app>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('generator-form');
    const resultContainer = document.getElementById('result-container');
    const generatedContent = document.getElementById('generated-content');
    const copyBtn = document.getElementById('copy-btn');
    const regenerateBtn = document.getElementById('regenerate-btn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            resultContainer.classList.remove('hidden');
            generatedContent.innerHTML = '<div class="flex justify-center"><svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
            
            // Get form data
            const formData = new FormData(form);
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Display generated content
                generatedContent.innerHTML = data.content;
                resultContainer.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                generatedContent.innerHTML = '<div class="text-red-500">An error occurred while generating the post. Please try again.</div>';
            });
        });
    }
    
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const content = generatedContent.textContent;
            navigator.clipboard.writeText(content).then(() => {
                // Show success message
                const originalText = copyBtn.innerHTML;
                copyBtn.innerHTML = '<svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Copied!';
                
                setTimeout(() => {
                    copyBtn.innerHTML = originalText;
                }, 2000);
            });
        });
    }
    
    if (regenerateBtn) {
        regenerateBtn.addEventListener('click', function() {
            form.dispatchEvent(new Event('submit'));
        });
    }
});
</script> 