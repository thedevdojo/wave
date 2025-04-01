<?php
    use function Laravel\Folio\{middleware};
    use Livewire\Volt\Component;
    use Livewire\WithFileUploads;
    use App\Models\Workspace;
    
    middleware(['auth', 'verified']);

    new class extends Component {
        use WithFileUploads;
        
        public Workspace $workspace;
        public $name = '';
        public $description = '';
        public $logo;
        public $newLogo;
        public $isOwner = false;
        public $isAdmin = false;
        
        public function mount(Workspace $workspace)
        {
            $this->workspace = $workspace;
            $this->name = $workspace->name;
            $this->description = $workspace->description;
            $this->logo = $workspace->logo;
            
            $user = auth()->user();
            $this->isOwner = $workspace->user_id === $user->id;
            
            // Check if user is admin in this workspace
            $member = $workspace->members()->where('user_id', $user->id)->first();
            $this->isAdmin = $member && $member->pivot->role === 'admin';
            
            // Redirect if not owner or admin
            if (!$this->isOwner && !$this->isAdmin) {
                return redirect()->route('workspaces.show', $workspace);
            }
        }
        
        public function rules()
        {
            return [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'newLogo' => 'nullable|image|max:1024', // 1MB Max
            ];
        }
        
        public function save()
        {
            $this->validate();
            
            $this->workspace->name = $this->name;
            $this->workspace->description = $this->description;
            
            if ($this->newLogo) {
                // Delete old logo if exists
                if ($this->workspace->logo) {
                    Storage::disk('public')->delete($this->workspace->logo);
                }
                
                $this->workspace->logo = $this->newLogo->store('workspace-logos', 'public');
                $this->logo = $this->workspace->logo;
            }
            
            $this->workspace->save();
            
            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Workspace updated successfully!'
            ]);
        }
        
        public function deleteLogo()
        {
            if ($this->workspace->logo) {
                Storage::disk('public')->delete($this->workspace->logo);
                $this->workspace->logo = null;
                $this->workspace->save();
                $this->logo = null;
                
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Logo removed successfully!'
                ]);
            }
        }
    }
?>

<x-layouts.app>
    @volt('workspaces-edit')
    <x-app.container class="lg:space-y-6">
        <x-app.heading
            title="Edit Workspace"
            description="Update workspace details"
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
        
        <div class="bg-white dark:bg-zinc-800 shadow overflow-hidden sm:rounded-lg">
            <form wire:submit="save" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center">
                            @if ($newLogo)
                                <div class="mr-4">
                                    <img src="{{ $newLogo->temporaryUrl() }}" alt="New Workspace Logo Preview" class="h-16 w-16 rounded-full object-cover">
                                </div>
                            @elseif ($logo)
                                <div class="mr-4">
                                    <img src="{{ Storage::url($logo) }}" alt="{{ $workspace->name }}" class="h-16 w-16 rounded-full object-cover">
                                </div>
                            @endif
                            <div>
                                <label for="newLogo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Workspace Logo
                                </label>
                                <div class="mt-1 flex items-center space-x-3">
                                    <input type="file" wire:model="newLogo" id="newLogo" class="sr-only" accept="image/*">
                                    <label for="newLogo" class="cursor-pointer py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Change Logo
                                    </label>
                                    
                                    @if ($logo)
                                        <button type="button" wire:click="deleteLogo" class="py-2 px-3 border border-red-300 dark:border-red-700 rounded-md shadow-sm text-sm leading-4 font-medium text-red-700 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Remove Logo
                                        </button>
                                    @endif
                                </div>
                                @error('newLogo') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Optional. JPG, PNG or GIF. Max 1MB.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Workspace Name
                        </label>
                        <div class="mt-1">
                            <input type="text" wire:model="name" id="name" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="My Brand or Client Name">
                        </div>
                        @error('name') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="col-span-1 md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <div class="mt-1">
                            <textarea wire:model="description" id="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md" placeholder="Brief description of this workspace (optional)"></textarea>
                        </div>
                        @error('description') <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="flex justify-end pt-5">
                    <a href="{{ route('workspaces.show', $workspace) }}" class="bg-white dark:bg-gray-800 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </x-app.container>
    @endvolt
</x-layouts.app> 