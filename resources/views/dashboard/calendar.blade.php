<?php
    use Livewire\Volt\Component;

    new class extends Component {
        public $notifyOnLaunch = false;

        public function mount()
        {
            $this->notifyOnLaunch = auth()->user()->notify_calendar_launch ?? false;
        }

        public function toggleNotification()
        {
            $user = auth()->user();
            $user->notify_calendar_launch = !$this->notifyOnLaunch;
            $user->save();
            
            $this->notifyOnLaunch = !$this->notifyOnLaunch;
        }
    }
?>

<x-layouts.app>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            @volt('calendar')
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Content Calendar</h1>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <div class="p-8">
                            <div class="max-w-3xl mx-auto text-center">
                                <div class="w-64 h-64 mx-auto mb-6">
                                    <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                                        <!-- Background Circle -->
                                        <circle cx="200" cy="200" r="180" fill="#F3F4F6" class="dark:fill-gray-700"/>
                                        
                                        <!-- Calendar Frame -->
                                        <rect x="100" y="80" width="200" height="240" rx="12" fill="white" class="dark:fill-gray-800" stroke="#E5E7EB" stroke-width="2"/>
                                        
                                        <!-- Calendar Header -->
                                        <rect x="100" y="80" width="200" height="60" rx="12" fill="#4F46E5"/>
                                        
                                        <!-- Calendar Grid -->
                                        <g stroke="#E5E7EB" stroke-width="1">
                                            <!-- Grid Lines -->
                                            <line x1="100" y1="160" x2="300" y2="160"/>
                                            <line x1="100" y1="200" x2="300" y2="200"/>
                                            <line x1="100" y1="240" x2="300" y2="240"/>
                                            <line x1="100" y1="280" x2="300" y2="280"/>
                                            <line x1="100" y1="320" x2="300" y2="320"/>
                                            
                                            <line x1="130" y1="120" x2="130" y2="320"/>
                                            <line x1="160" y1="120" x2="160" y2="320"/>
                                            <line x1="190" y1="120" x2="190" y2="320"/>
                                            <line x1="220" y1="120" x2="220" y2="320"/>
                                            <line x1="250" y1="120" x2="250" y2="320"/>
                                            <line x1="280" y1="120" x2="280" y2="320"/>
                                            <line x1="310" y1="120" x2="310" y2="320"/>
                                        </g>
                                        
                                        <!-- Sample Content -->
                                        <g>
                                            <!-- Highlighted Date -->
                                            <circle cx="190" cy="200" r="15" fill="#4F46E5" opacity="0.1"/>
                                            
                                            <!-- Sample Event -->
                                            <rect x="150" y="220" width="80" height="20" rx="4" fill="#4F46E5" opacity="0.1"/>
                                        </g>
                                        
                                        <!-- Decorative Elements -->
                                        <circle cx="130" cy="110" r="4" fill="white" opacity="0.5"/>
                                        <circle cx="270" cy="110" r="4" fill="white" opacity="0.5"/>
                                        
                                        <!-- Decorative Dots -->
                                        <circle cx="100" cy="200" r="3" fill="#4F46E5" opacity="0.2"/>
                                        <circle cx="300" cy="200" r="3" fill="#4F46E5" opacity="0.2"/>
                                        <circle cx="200" cy="100" r="3" fill="#4F46E5" opacity="0.2"/>
                                        <circle cx="200" cy="300" r="3" fill="#4F46E5" opacity="0.2"/>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">✨ Your social media's new best friend</h2>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    Get ready to meet the calendar that's about to change your social media game! 
                                    <br><br>
                                    We're building something special - a calendar that makes planning your content as easy as pie. No more last-minute scrambles or forgotten posts. Just smooth sailing and perfectly timed content that your audience will love.
                                    <br><br>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-medium">Coming soon to make your life easier!</span>
                                </p>
                                
                                <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
                                    <div class="flex items-center justify-center space-x-3">
                                        <label for="notify-toggle" class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                            Want to be first in line? 🎯
                                        </label>
                                        <button 
                                            wire:click="toggleNotification"
                                            type="button" 
                                            role="switch"
                                            aria-checked="{{ $notifyOnLaunch ? 'true' : 'false' }}"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 {{ $notifyOnLaunch ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}"
                                        >
                                            <span 
                                                aria-hidden="true" 
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $notifyOnLaunch ? 'translate-x-5' : 'translate-x-0' }}"
                                            ></span>
                                        </button>
                                    </div>
                                    <p class="mt-2 text-sm text-blue-600/80 dark:text-blue-400/80">
                                        We'll let you know the moment it's ready to rock! 🚀
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endvolt
        </div>
    </div>
</x-layouts.app> 