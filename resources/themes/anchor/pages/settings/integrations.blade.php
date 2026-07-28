<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Filament\Notifications\Notification;
use App\Models\NylasAccount;
use App\Models\NylasWebhookEvent;
use App\Services\NylasService;

middleware('auth');
name('settings.integrations');

new class extends Component
{
    // Form properties
    public string $toEmail = '';
    public string $subject = '';
    public string $body = '';
    public string $selectedAccountId = '';

    public function mount()
    {
        $firstAccount = auth()->user()->nylasAccounts()->first();
        if ($firstAccount) {
            $this->selectedAccountId = (string) $firstAccount->id;
        }
    }

    public function getAccounts()
    {
        return auth()->user()->nylasAccounts;
    }

    public function getWebhookEvents()
    {
        return NylasWebhookEvent::latest()->take(10)->get();
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

    public function clearWebhookEvents()
    {
        NylasWebhookEvent::truncate();

        Notification::make()
            ->title('Logs Cleared')
            ->body('Webhook event logs have been successfully cleared.')
            ->success()
            ->send();
    }

    public function sendTestEmail(NylasService $nylasService)
    {
        $this->validate([
            'toEmail' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'selectedAccountId' => 'required|exists:nylas_accounts,id',
        ]);

        $account = auth()->user()->nylasAccounts()->where('id', $this->selectedAccountId)->first();

        if (!$account) {
            Notification::make()
                ->title('Account error')
                ->body('Selected Nylas account not found.')
                ->danger()
                ->send();
            return;
        }

        $payload = [
            'to' => [
                ['email' => $this->toEmail]
            ],
            'subject' => $this->subject,
            'body' => $this->body,
        ];

        $response = $nylasService->sendMessage($account->grant_id, $payload);

        if ($response && isset($response['data']['id'])) {
            Notification::make()
                ->title('Email Sent successfully!')
                ->body('The email was successfully dispatched through the Nylas API.')
                ->success()
                ->send();

            // Clear the form fields upon success
            $this->toEmail = '';
            $this->subject = '';
            $this->body = '';
        } else {
            Notification::make()
                ->title('Sending Failed')
                ->body('Failed to send the email. Please check your connection and configuration.')
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

                    <!-- Nylas Connections Card -->
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

                    <!-- Send Test Email & Webhooks Stream (Only visible if accounts are connected) -->
                    @if($accounts->isNotEmpty())
                        <!-- Send Email Form -->
                        <x-card class="p-6">
                            <form wire:submit.prevent="sendTestEmail" class="space-y-4">
                                <div class="space-y-1">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                                        <x-phosphor-paper-plane-tilt-duotone class="w-6 h-6 text-indigo-600" />
                                        Send Test Email
                                    </h3>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                        Dispatch a live test email directly through Nylas to verify your configuration.
                                    </p>
                                </div>

                                <div class="space-y-3">
                                    <div>
                                        <label for="selectedAccountId" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">From Account</label>
                                        <select wire:model="selectedAccountId" id="selectedAccountId" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-zinc-900 dark:text-zinc-100">
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->email }}</option>
                                            @endforeach
                                        </select>
                                        @error('selectedAccountId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="toEmail" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">To Email</label>
                                        <input type="email" wire:model="toEmail" id="toEmail" placeholder="recipient@example.com" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-zinc-900 dark:text-zinc-100" />
                                        @error('toEmail') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="subject" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">Subject</label>
                                        <input type="text" wire:model="subject" id="subject" placeholder="Hello from Nylas!" class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-zinc-900 dark:text-zinc-100" />
                                        @error('subject') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="body" class="block text-xs font-medium text-zinc-700 dark:text-zinc-300">Body</label>
                                        <textarea wire:model="body" id="body" rows="4" placeholder="Type your email body here..." class="mt-1 block w-full rounded-md border-zinc-300 dark:border-zinc-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm dark:bg-zinc-900 dark:text-zinc-100"></textarea>
                                        @error('body') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition-colors cursor-pointer">
                                        Send Email
                                    </button>
                                </div>
                            </form>
                        </x-card>

                        <!-- Live Webhook Logs Stream -->
                        <x-card class="p-6">
                            <div wire:poll.5s class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                                            <x-phosphor-broadcast-duotone class="w-6 h-6 text-indigo-600 animate-pulse" />
                                            Live Webhook Event Stream
                                        </h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                            Instantly shows emails received or updated via webhooks. Automatically updates every 5s.
                                        </p>
                                    </div>

                                    @php
                                        $events = $this->getWebhookEvents();
                                    @endphp

                                    @if($events->isNotEmpty())
                                        <button wire:click="clearWebhookEvents" class="text-xs text-red-600 dark:text-red-400 hover:underline">
                                            Clear logs
                                        </button>
                                    @endif
                                </div>

                                @if($events->isEmpty())
                                    <div class="p-6 bg-zinc-50 dark:bg-zinc-900 border border-dashed border-zinc-200 dark:border-zinc-800 rounded-lg text-center space-y-2">
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-400">
                                            <x-phosphor-arrows-left-right-duotone class="w-5 h-5" />
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-sm font-medium text-zinc-900 dark:text-zinc-100">No Webhook Events Received Yet</h4>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">
                                                When a webhook is triggered (e.g., you receive an email), it will be captured and stream in live here.
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <div class="space-y-3">
                                        @foreach($events as $event)
                                            <div x-data="{ open: false }" class="p-3 border border-zinc-200 dark:border-zinc-800 rounded-lg bg-zinc-50 dark:bg-zinc-900/50 space-y-2">
                                                <div class="flex items-center justify-between cursor-pointer" @click="open = !open">
                                                    <div class="flex items-center gap-2">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800">
                                                            {{ $event->event_type }}
                                                        </span>
                                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 font-mono">
                                                            Grant: {{ Str::limit($event->grant_id, 12) }}
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs text-zinc-400 font-medium">
                                                            {{ $event->created_at->diffForHumans() }}
                                                        </span>
                                                        <x-phosphor-caret-down-bold class="w-3.5 h-3.5 text-zinc-400 transition-transform duration-200" ::class="{ 'transform rotate-180': open }" />
                                                    </div>
                                                </div>

                                                <div x-show="open" x-collapse x-cloak class="pt-2 border-t border-zinc-200 dark:border-zinc-800 text-xs text-zinc-600 dark:text-zinc-300 font-mono overflow-x-auto bg-zinc-100 dark:bg-zinc-950 p-2 rounded max-h-60">
                                                    <pre class="whitespace-pre-wrap">{{ json_encode($event->payload, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </x-card>
                    @endif
                </div>
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
