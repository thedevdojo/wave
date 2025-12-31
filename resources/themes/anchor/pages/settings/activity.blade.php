<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\ActivityLog;

middleware('auth');
name('settings.activity');

new class extends Component
{
    use WithPagination;

    public string $filterAction = '';
    public string $search = '';

    public function with(): array
    {
        $query = auth()->user()->activityLogs()
            ->orderBy('created_at', 'desc');

        if ($this->filterAction) {
            $query->where('action', $this->filterAction);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('action', 'like', '%' . $this->search . '%');
            });
        }

        return [
            'activities' => $query->paginate(15),
            'actionTypes' => auth()->user()->activityLogs()
                ->select('action')
                ->distinct()
                ->pluck('action')
                ->toArray(),
        ];
    }

    public function clearFilters()
    {
        $this->filterAction = '';
        $this->search = '';
        $this->resetPage();
    }

    public function getActivityIcon($action)
    {
        return match(true) {
            str_contains($action, 'password') => 'phosphor-lock-duotone',
            str_contains($action, 'email') => 'phosphor-envelope-duotone',
            str_contains($action, 'api') => 'phosphor-code-duotone',
            str_contains($action, 'login') => 'phosphor-sign-in-duotone',
            str_contains($action, 'profile') => 'phosphor-user-duotone',
            str_contains($action, 'subscription') => 'phosphor-credit-card-duotone',
            str_contains($action, 'delete') => 'phosphor-trash-duotone',
            default => 'phosphor-clock-duotone',
        };
    }

    public function getActivityColor($action)
    {
        return match(true) {
            str_contains($action, 'delete') => 'text-red-600 dark:text-red-400',
            str_contains($action, 'password') || str_contains($action, 'security') => 'text-orange-600 dark:text-orange-400',
            str_contains($action, 'login') => 'text-green-600 dark:text-green-400',
            default => 'text-blue-600 dark:text-blue-400',
        };
    }
};

?>

<x-layouts.app>
    @volt('settings.activity')
        <div class="relative">
            <x-app.settings-layout
                title="Activity Log"
                description="View your account activity history and security events.">
                
                <div class="w-full max-w-4xl space-y-6">
                    
                    <!-- Filters -->
                    <x-card class="p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-end">
                            <div class="flex-1">
                                <label for="search" class="block mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">Search</label>
                                <input 
                                    wire:model.live.debounce.300ms="search"
                                    type="text" 
                                    id="search"
                                    placeholder="Search activities..."
                                    class="w-full px-4 py-2 text-sm border rounded-lg bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>

                            <div class="flex-1">
                                <label for="filterAction" class="block mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">Filter by Action</label>
                                <select 
                                    wire:model.live="filterAction"
                                    id="filterAction"
                                    class="w-full px-4 py-2 text-sm border rounded-lg bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-600 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="">All Actions</option>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($filterAction || $search)
                                <button 
                                    wire:click="clearFilters"
                                    class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors"
                                >
                                    Clear Filters
                                </button>
                            @endif
                        </div>
                    </x-card>

                    <!-- Activity List -->
                    <x-card class="p-6">
                        @if($activities->isEmpty())
                            <div class="py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-zinc-100">No activity found</h3>
                                <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                                    @if($filterAction || $search)
                                        Try adjusting your filters to find what you're looking for.
                                    @else
                                        Your account activity will appear here.
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($activities as $activity)
                                    <div class="flex gap-4 p-4 transition-colors border rounded-lg border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800">
                                                <x-dynamic-component 
                                                    :component="$this->getActivityIcon($activity->action)" 
                                                    class="w-5 h-5 {{ $this->getActivityColor($activity->action) }}"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                        {{ ucwords(str_replace('_', ' ', $activity->action)) }}
                                                    </p>
                                                    @if($activity->description)
                                                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                                            {{ $activity->description }}
                                                        </p>
                                                    @endif
                                                    <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-xs text-zinc-500 dark:text-zinc-500">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                            {{ $activity->created_at->diffForHumans() }}
                                                        </span>
                                                        @if($activity->ip_address)
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                                                </svg>
                                                                {{ $activity->ip_address }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <span class="text-xs text-zinc-500 dark:text-zinc-500">
                                                    {{ $activity->created_at->format('M j, Y g:i A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $activities->links() }}
                            </div>
                        @endif
                    </x-card>

                    <!-- Security Notice -->
                    <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                        <div class="flex gap-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Security Tip</h4>
                                <p class="mt-1 text-sm text-blue-800 dark:text-blue-200">
                                    Review your activity log regularly to ensure all actions were performed by you. If you notice any suspicious activity, change your password immediately and contact support.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
