<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    use App\Models\Workspace;
    
    middleware('auth');

    new class extends Component {
        public $ownedWorkspaces = [];
        public $memberWorkspaces = [];
        
        public function mount()
        {
            $user = auth()->user();
            
            // Get workspaces owned by the user
            $this->ownedWorkspaces = $user->ownedWorkspaces;
            
            // Get workspaces where the user is a member
            $this->memberWorkspaces = $user->memberWorkspaces;
        }
    }
?>

<x-layouts.app>
    @volt('workspaces-index')
    <x-app.container x-data class="lg:space-y-6" x-cloak>
        
        <x-app.heading
            title="Workspaces"
            description="Manage your brands and client workspaces"
            :border="false"
        />
        
        <div class="flex flex-col">
            <!-- Main Content -->
            <div class="w-full">
                <!-- Owned Workspaces Section -->
                <div class="p-5 w-full bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your Workspaces</h2>
                        
                        @if(auth()->user()->hasAgencyPlan())
                            <a href="{{ route('workspaces.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Create Workspace
                            </a>
                        @else
                            <a href="{{ route('settings.subscription') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                Upgrade to Agency Plan
                            </a>
                        @endif
                    </div>
                    
                    @if($ownedWorkspaces->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-gray-100">No workspaces yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new workspace.</p>
                            
                            @if(auth()->user()->hasAgencyPlan())
                                <div class="mt-6">
                                    <a href="{{ route('workspaces.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                        Create Workspace
                                    </a>
                                </div>
                            @else
                                <div class="mt-6">
                                    <a href="{{ route('settings.subscription') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        Upgrade to Agency Plan
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Default Workspace -->
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden {{ !session('current_workspace_id') ? 'ring-2 ring-blue-500' : '' }}">
                                <div class="p-5">
                                    <div class="flex items-center mb-3">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-400 mr-3">
                                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Default Workspace</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Personal</p>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Your personal workspace for individual content.</p>
                                    
                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ !session('current_workspace_id') ? 'Current workspace' : '' }}
                                        </span>
                                        <a href="{{ route('workspace.switch', 'personal') }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md {{ !session('current_workspace_id') ? 'text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100' : 'text-white bg-blue-600 hover:bg-blue-700' }} focus:outline-none">
                                            {{ !session('current_workspace_id') ? 'Current' : 'Switch to' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            @foreach($ownedWorkspaces as $workspace)
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden {{ session('current_workspace_id') == $workspace->id ? 'ring-2 ring-blue-500' : '' }}">
                                    <div class="p-5">
                                        <div class="flex items-center mb-3">
                                            @if($workspace->logo)
                                                <img src="{{ Storage::url($workspace->logo) }}" alt="{{ $workspace->name }}" class="h-10 w-10 rounded-full mr-3">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                                    {{ substr($workspace->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $workspace->name }}</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Owner</p>
                                            </div>
                                        </div>
                                        
                                        @if($workspace->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($workspace->description, 100) }}</p>
                                        @endif
                                        
                                        <div class="flex justify-between items-center mt-4">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ session('current_workspace_id') == $workspace->id ? 'Current workspace' : '' }}
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('workspaces.show', $workspace) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                    View Details
                                                </a>
                                                <a href="{{ route('workspace.switch', $workspace->id) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md {{ session('current_workspace_id') == $workspace->id ? 'text-green-800 bg-green-100 dark:bg-green-800 dark:text-green-100' : 'text-white bg-blue-600 hover:bg-blue-700' }} focus:outline-none">
                                                    {{ session('current_workspace_id') == $workspace->id ? 'Current' : 'Switch to' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <!-- Agency Plan Upgrade Section -->
                @if(!auth()->user()->hasAgencyPlan())
                    <div class="p-5 w-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg shadow-lg mb-6">
                        <div class="flex flex-col md:flex-row items-center justify-between">
                            <div class="mb-4 md:mb-0 md:mr-8">
                                <h3 class="text-xl font-bold text-white mb-2">Upgrade to Agency Plan</h3>
                                <p class="text-white/90 mb-4 max-w-xl">
                                    Get access to multiple workspaces, team collaboration, and more advanced features with our Agency Plan.
                                </p>
                                <a href="{{ route('settings.subscription') }}" class="inline-flex items-center px-4 py-2 bg-white rounded-md font-medium text-blue-600 hover:bg-white/90 transition-colors">
                                    View Plans
                                    <svg class="ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <svg class="h-32 md:h-40 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app> 