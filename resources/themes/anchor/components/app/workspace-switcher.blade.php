<div x-data="{ workspaceSwitcherOpen: false }" class="relative mt-1">
    <button 
        @click="workspaceSwitcherOpen = !workspaceSwitcherOpen" 
        class="flex w-full items-center justify-between p-2.5 text-sm rounded-lg bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-700/70"
    >
        <div class="flex items-center">
            @if(session('current_workspace_id') && $currentWorkspace)
                @if($currentWorkspace->logo)
                    <img src="{{ Storage::url($currentWorkspace->logo) }}" alt="{{ $currentWorkspace->name }}" class="w-5 h-5 rounded-full mr-2">
                @else
                    <div class="flex items-center justify-center w-5 h-5 rounded-full bg-blue-500 text-white font-bold text-xs mr-2">
                        {{ substr($currentWorkspace->name, 0, 1) }}
                    </div>
                @endif
                <span class="truncate max-w-[140px]">{{ $currentWorkspace->name }}</span>
            @else
                <div class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-400 mr-2">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <span>Default Workspace</span>
            @endif
        </div>
        <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <!-- Dropdown content -->
    <div 
        x-show="workspaceSwitcherOpen" 
        @click.away="workspaceSwitcherOpen = false"
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="transform opacity-0 scale-95" 
        x-transition:enter-end="transform opacity-100 scale-100" 
        x-transition:leave="transition ease-in duration-75" 
        x-transition:leave-start="transform opacity-100 scale-100" 
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute left-0 z-50 w-full mt-1 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-lg shadow-lg max-h-60 overflow-y-auto"
        style="display: none;"
    >
        <div class="p-2 space-y-1">
            <!-- Personal workspace option -->
            <a 
                href="{{ route('workspace.switch', ['workspaceId' => 'personal']) }}"
                class="flex items-center p-2 rounded-md hover:bg-gray-100 dark:hover:bg-zinc-700 {{ !session('current_workspace_id') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}"
            >
                <div class="flex items-center justify-center w-5 h-5 rounded-full bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-400 mr-2">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <span class="text-sm">Default Workspace</span>
                @if(!session('current_workspace_id'))
                    <svg class="w-4 h-4 ml-auto text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                @endif
            </a>

            <!-- Workspaces list -->
            @php
                $ownedWorkspaces = auth()->user()->ownedWorkspaces;
                $hasWorkspaces = $ownedWorkspaces->count() > 0;
            @endphp

            @if($hasWorkspaces)
                <div class="h-px bg-gray-200 dark:bg-zinc-700 my-1"></div>
                
                @if($ownedWorkspaces->count() > 0)
                    <div class="px-2 py-1 text-xs font-medium text-gray-500 dark:text-gray-400">Your Workspaces</div>
                    @foreach($ownedWorkspaces as $workspace)
                        <a 
                            href="{{ route('workspace.switch', $workspace->id) }}"
                            class="flex items-center p-2 rounded-md hover:bg-gray-100 dark:hover:bg-zinc-700 {{ session('current_workspace_id') == $workspace->id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300' : '' }}"
                        >
                            @if($workspace->logo)
                                <img src="{{ Storage::url($workspace->logo) }}" alt="{{ $workspace->name }}" class="w-5 h-5 rounded-full mr-2">
                            @else
                                <div class="flex items-center justify-center w-5 h-5 rounded-full bg-blue-500 text-white font-bold text-xs mr-2">
                                    {{ substr($workspace->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm truncate max-w-[140px]">{{ $workspace->name }}</span>
                            @if(session('current_workspace_id') == $workspace->id)
                                <svg class="w-4 h-4 ml-auto text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </a>
                    @endforeach
                @endif
            @endif

            <!-- Workspace management -->
            <div class="h-px bg-gray-200 dark:bg-zinc-700 my-1"></div>
            <a href="{{ route('workspaces.index') }}" class="flex items-center p-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-zinc-700">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
                Manage Workspaces
            </a>
            @if(auth()->user()->hasAgencyPlan())
                <a href="{{ route('workspaces.create') }}" class="flex items-center p-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-zinc-700">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Workspace
                </a>
            @endif
        </div>
    </div>
</div> 