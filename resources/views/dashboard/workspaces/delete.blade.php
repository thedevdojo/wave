<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    use App\Models\Workspace;
    use Illuminate\Support\Facades\Storage;
    
    middleware(['auth', 'verified']);

    new class extends Component {
        public Workspace $workspace;
        public $isOwner = false;
        
        public function mount(Workspace $workspace)
        {
            $this->workspace = $workspace;
            
            // Check if user is the owner of this workspace
            $this->isOwner = $workspace->user_id === auth()->id();
            
            // Redirect if not owner
            if (!$this->isOwner) {
                return redirect()->route('workspaces.show', $workspace)
                    ->with('error', 'Only the workspace owner can delete this workspace.');
            }
        }
        
        public function deleteWorkspace()
        {
            // Double check if user is the owner
            if (!$this->isOwner) {
                return redirect()->route('workspaces.show', $this->workspace)
                    ->with('error', 'Only the workspace owner can delete this workspace.');
            }
            
            // Delete workspace logo if exists
            if ($this->workspace->logo) {
                Storage::disk('public')->delete($this->workspace->logo);
            }
            
            // Get workspace ID for session check
            $workspaceId = $this->workspace->id;
            
            // Detach all workspace members to prevent relationship issues
            $this->workspace->members()->detach();
            
            // Delete the workspace settings
            $this->workspace->settings()->delete();
            
            // Delete workspace
            $this->workspace->delete();
            
            // Clear current workspace session if it was the deleted one
            if (session('current_workspace_id') == $workspaceId) {
                session()->forget('current_workspace_id');
            }
            
            // Redirect to workspaces index
            return redirect()->route('workspaces.index')
                ->with('success', 'Workspace deleted successfully.');
        }
    }
?>

<x-layouts.app>
    @volt('workspaces-delete')
    <x-app.container class="lg:space-y-6">
        <x-app.heading
            title="Delete Workspace"
            description="Please confirm you want to delete this workspace"
            :border="false"
        >
            <x-slot:actions>
                <a href="{{ route('workspaces.show', $workspace) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Back to Workspace
                </a>
            </x-slot:actions>
        </x-app.heading>

        <div class="bg-white dark:bg-zinc-800 shadow overflow-hidden sm:rounded-lg border border-red-300 dark:border-red-700">
            <div class="px-4 py-5 sm:px-6 bg-red-50 dark:bg-red-900/20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10 mr-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-red-800 dark:text-red-300">
                        Warning: This action cannot be undone
                    </h3>
                </div>
            </div>
            
            <div class="border-t border-gray-200 dark:border-gray-700">
                <dl>
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Workspace Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            <div class="flex items-center">
                                @if($workspace->logo)
                                    <img src="{{ Storage::url($workspace->logo) }}" alt="{{ $workspace->name }}" class="h-8 w-8 rounded-full mr-3">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ substr($workspace->name, 0, 1) }}
                                    </div>
                                @endif
                                {{ $workspace->name }}
                            </div>
                        </dd>
                    </div>
                    
                    @if($workspace->description)
                        <div class="bg-white dark:bg-zinc-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Description
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                                {{ $workspace->description }}
                            </dd>
                        </div>
                    @endif
                    
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Created on
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                            {{ $workspace->created_at->format('M d, Y') }}
                        </dd>
                    </div>
                    
                    <div class="bg-white dark:bg-zinc-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Consequences
                        </dt>
                        <dd class="mt-1 text-sm text-red-600 dark:text-red-400 sm:mt-0 sm:col-span-2">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>All workspace settings will be permanently deleted</li>
                                <li>All content associated with this workspace will be removed</li>
                                <li>All member associations will be removed</li>
                                <li>If this is your current workspace, you will be switched to the default workspace</li>
                            </ul>
                        </dd>
                    </div>
                </dl>
            </div>
            
            <div class="px-4 py-5 bg-gray-50 dark:bg-zinc-900/50 sm:px-6 flex justify-end space-x-3">
                <a href="{{ route('workspaces.show', $workspace) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-zinc-800 hover:bg-gray-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                
                <button wire:click="deleteWorkspace" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Delete Workspace
                </button>
            </div>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app> 