<?php
    use function Laravel\Folio\{middleware};
    middleware('auth');
?>

<x-layouts.app>
    <x-app.container x-data class="lg:space-y-6" x-cloak>

        <div class="mt-6">
            <a href="{{ route('inspiration.index') }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inspiration Feed
            </a>
        </div>
        
        <x-app.heading
            title="Manage Your Interests"
            description="Select topics that interest you to personalize your inspiration feed"
            :border="false"
        />
        
        <div id="success-alert" class="hidden mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:text-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>Your interests have been saved successfully!</span>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 p-6">
            <form id="interests-form" action="{{ route('inspiration.update_interests') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Select Your Interests</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Choose topics that interest you to personalize your inspiration feed. We'll use these to suggest content and trending topics.</p>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($allTags as $tag)
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="tag-{{ $tag->id }}" 
                                    name="tag_ids[]" 
                                    value="{{ $tag->id }}" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-zinc-700 dark:border-zinc-600"
                                    {{ in_array($tag->id, $userInterestIds) ? 'checked' : '' }}
                                >
                                <label for="tag-{{ $tag->id }}" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex justify-start">
                    <button 
                        id="save-button" 
                        type="button" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800"
                    >
                        Save Interests
                    </button>
                </div>
            </form>
        </div>
        
    </x-app.container>
</x-layouts.app>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded');
    
    const form = document.getElementById('interests-form');
    const saveButton = document.getElementById('save-button');
    const successAlert = document.getElementById('success-alert');
    
    console.log('Form:', form);
    console.log('Save button:', saveButton);
    console.log('Success alert:', successAlert);
    
    if (saveButton) {
        saveButton.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Save button clicked');
            
            // Disable button and show loading state
            saveButton.disabled = true;
            saveButton.innerHTML = 'Saving...';
            
            // Get form data
            const formData = new FormData(form);
            console.log('CSRF token:', formData.get('_token'));
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
                
                // Reset button state
                saveButton.disabled = false;
                saveButton.innerHTML = 'Save Interests';
                
                // Show success message
                successAlert.classList.remove('hidden');
                
                // Hide success message after 3 seconds
                setTimeout(() => {
                    successAlert.classList.add('hidden');
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button state
                saveButton.disabled = false;
                saveButton.innerHTML = 'Save Interests';
                
                // Show error message
                alert('An error occurred while saving your interests. Please try again.');
            });
        });
    }
});
</script> 