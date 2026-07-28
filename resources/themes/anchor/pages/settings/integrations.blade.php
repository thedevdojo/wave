<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Filament\Notifications\Notification;
use App\Models\NylasAccount;

middleware('auth');
name('settings.integrations');

new class extends Component
{
    public function getAccounts()
    {
        return auth()->user()->nylasAccounts;
    }

    public function disconnect($id)
    {
        $account = auth()->user()->nylasAccounts()->where('id', $id)->first();

        if ($account) {
            $account->delete();

            Notification::make()
                ->title('Account disconnected')
                ->body('Your email and calendar connection has been removed.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Account not found')
                ->danger()
                ->send();
        }
    }
};

?>

<x-layouts.app>
    @volt('settings.integrations')
        <div class="relative">
            <x-app.settings-layout
                title="Integrations"
                description="Manage your third-party integrations and connected accounts."
            >
                <div class="w-full max-w-2xl space-y-6">

                    <!-- Session Status Alert -->
                    @if (session('success'))
                        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-200" role="alert">
                            <span class="font-medium">Success!</span> {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 border border-red-200" role="alert">
                            <span class="font-medium">Error:</span> {{ session('error') }}
                        </div>
                    @endif

                    <x-card class="p-6">
                        <div class="space-y-6">
                            <div class="flex items-start justify-between">
                                <div class="space-y-1">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                                        <x-phosphor-calendar-duotone class="w-6 h-6 text-indigo-600" />
                                        Nylas Email & Calendar Sync
                                    </h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        Connect your Google, Microsoft, or IMAP account to sync your email messages and calendar events.
                                    </p>
                                </div>
                            </div>

                            @php
                                $accounts = $this->getAccounts();
                            @endphp

                            @if($accounts->isEmpty())
                                <div class="p-6 bg-zinc-50 dark:bg-zinc-900 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-lg text-center space-y-4">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600">
                                        <x-phosphor-plugs-duotone class="w-6 h-6" />
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">No accounts connected</h4>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">
                                            To sync your calendar events and email messages, connect your account securely.
                                        </p>
                                    </div>
                                    <div class="pt-2">
                                        <a href="{{ route('nylas.connect') }}"
                                           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition-colors cursor-pointer">
                                            Connect Google Calendar & Email
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="divide-y divide-zinc-200 dark:divide-zinc-800 border border-zinc-200 dark:border-zinc-800 rounded-lg overflow-hidden bg-white dark:bg-zinc-950">
                                    @foreach($accounts as $account)
                                        <div class="p-4 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 flex items-center justify-center">
                                                    <x-phosphor-envelope-duotone class="w-5 h-5" />
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">
                                                        {{ $account->email }}
                                                    </h4>
                                                    <div class="flex items-center gap-1.5 mt-0.5">
                                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        <span class="text-xs text-zinc-500 dark:text-zinc-400">Connected</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <button
                                                    wire:click="disconnect({{ $account->id }})"
                                                    wire:confirm="Are you sure you want to disconnect this account?"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-50 hover:dark:bg-zinc-800 rounded-md text-xs font-medium text-zinc-700 dark:text-zinc-300 transition-colors">
                                                    Disconnect
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="pt-2 text-center">
                                    <a href="{{ route('nylas.connect') }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <x-phosphor-plus-bold class="w-3 h-3" /> Connect another account
                                    </a>
                                </div>
                            @endif

                        </div>
                    </x-card>
                </div>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
