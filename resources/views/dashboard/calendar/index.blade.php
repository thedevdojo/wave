<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;

    middleware('auth');

    new class extends Component {
        public $notifyOnLaunch = false;
        public $currentWorkspaceId = null;

        public function mount()
        {
            $this->currentWorkspaceId = session('current_workspace_id');
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
    @volt('calendar.index')
    <x-app.container x-data class="lg:space-y-6" x-cloak>
        
        <x-app.heading
            title="Content calendar"
            description="Plan and schedule your social media content"
            :border="false"
        />
        
        <div class="flex flex-col">
            <!-- Main Content -->
            <div class="w-full">
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                    <div class="max-w-3xl mx-auto text-center">
                        <div class="mx-auto mt-20 text-center">
                            <img 
                                src="{{ asset('images/calendar-img.png') }}" 
                                alt="Calendar illustration"
                                width="150"
                                class="mx-auto mb-4"
                                height="150"
                            />
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">Coming soon</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            We're building something special - a calendar that makes planning your content supa easy. <br/> No more last-minute scrambles or forgotten posts. Plan the content you need to post in advance.
                        </p>
                        
                        <div class="mt-8 p-6 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
                            <div class="flex items-center justify-center space-x-3">
                                <label for="notify-toggle" class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    Want to be notified upon launch?
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
                                We'll send you an email as soon as the content calendar is available.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app> 