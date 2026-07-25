<?php

use Filament\Forms\Components\TextInput;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Routing\Attributes\Controllers\Middleware;
/* @chisel-passkeys */
use Laravel\Passkeys\Actions\DeletePasskey;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
/* @end-chisel-passkeys */

use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Wave\ActivityLog;

new
#[Layout('theme::components.layouts.app')]
#[Middleware(Authenticate::class)]
class extends Component implements HasForms {
    use InteractsWithForms;

    /* @chisel-passkeys */
    #[Locked]
    public bool $canManagePasskeys;

    #[Locked]
    public array $passkeys = [];

    public bool $showDeleteModal = false;

    #[Locked]
    public ?int $deletingPasskeyId = null;

    #[Locked]
    public string $deletingPasskeyName = '';
    /* @end-chisel-passkeys */

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();

        /* @chisel-passkeys */
        $this->canManagePasskeys = config('devdojo.auth.settings.enable_passkeys');

        if ($this->canManagePasskeys) {
            $this->loadPasskeys();
        }
        /* @end-chisel-passkeys */
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('current_password')
                    ->label('Current Password')
                    ->required()
                    ->currentPassword()
                    ->password()
                    ->revealable(),
                TextInput::make('password')
                    ->label('New Password')
                    ->required()
                    ->minLength(config('wave.auth.min_password_length'))
                    ->password()
                    ->revealable(),
                TextInput::make('password_confirmation')
                    ->label('Confirm New Password')
                    ->required()
                    ->password()
                    ->revealable()
                    ->same('password')
                // ...
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();
        $this->validate();

        auth()->user()->forceFill([
            'password' => bcrypt($state['password'])
        ])->save();

        // Log the activity
        ActivityLog::log(
            'password_changed',
            'Password was successfully changed'
        );

        $this->form->fill();

        Notification::make()
            ->title('Successfully changed password')
            ->success()
            ->send();
    }

    /* @chisel-passkeys */
    /**
     * Load the user's passkeys.
     */
    public function loadPasskeys(): void
    {
        $this->passkeys = auth()->user()->passkeys()
            ->select(['id', 'name', 'credential', 'created_at', 'last_used_at'])
            ->latest()
            ->get()
            ->map(fn($passkey) => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'authenticator' => $passkey->authenticator,
                'created_at_diff' => $passkey->created_at->diffForHumans(),
                'last_used_at_diff' => $passkey->last_used_at?->diffForHumans(),
            ])
            ->toArray();
    }

    /**
     * Show the delete confirmation modal.
     */
    public function confirmDelete(int $passkeyId): void
    {
        $passkey = auth()->user()->passkeys()->findOrFail($passkeyId);

        $this->deletingPasskeyId = $passkey->id;
        $this->deletingPasskeyName = $passkey->name;
        $this->showDeleteModal = true;
    }

    /**
     * Delete the passkey.
     */
    public function deletePasskey(DeletePasskey $deletePasskey): void
    {
        if (!$this->deletingPasskeyId) {
            return;
        }

        $passkey = auth()->user()->passkeys()->findOrFail($this->deletingPasskeyId);

        $deletePasskey(auth()->user(), $passkey);

        $this->closeDeleteModal();
        $this->loadPasskeys();
    }

    /**
     * Close the delete confirmation modal.
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingPasskeyId = null;
        $this->deletingPasskeyName = '';
    }
    /* @end-chisel-passkeys */

}

?>
<div>
    <div class="relative">
        <x-app.settings-layout
            title="Security"
            description="Update and change your current account password."
        >
            <form wire:submit="save" class="w-full max-w-lg">
                {{ $this->form }}
                <div class="w-full pt-6 text-right">
                    <x-button type="submit">Save</x-button>
                </div>
            </form>

            @if (config('devdojo.auth.settings.enable_2fa'))
                <section class="mt-12 w-full max-w-lg">
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('Two-factor authentication') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage your two-factor authentication settings.') }}</p>

                    <div class="mt-6 flex justify-end">
                        <x-button href="{{ route('user.two-factor-authentication') }}" tag="a" wire:navigate>
                            {{ __('Two Factor Authentication Settings') }}
                        </x-button>
                    </div>
                </section>
            @endif

            @if ($canManagePasskeys)
                <section class="mt-12 w-full max-w-lg">
                    <h3 class="text-lg font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ __('Passkeys') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Manage your passkeys for passwordless sign-in') }}</p>

                    <div class="mt-6 flex flex-col space-y-6 text-sm" wire:cloak>
                        <div class="overflow-hidden rounded-lg border border-zinc-200 dark:border-zinc-700">
                            @forelse ($passkeys as $passkey)
                                <div class="flex items-center justify-between p-4 {{ ! $loop->last ? 'border-b border-zinc-200 dark:border-zinc-700' : '' }}">
                                    <div class="flex items-center gap-4">
                                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-zinc-100 dark:bg-zinc-800">
                                            <x-phosphor-key-duotone class="size-5 text-zinc-500 dark:text-zinc-400" />
                                        </div>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2.5">
                                                <p class="font-medium tracking-tight text-zinc-900 dark:text-zinc-100">{{ $passkey['name'] }}</p>
                                                @if ($passkey['authenticator'])
                                                    <span class="inline-flex items-center rounded-md border border-zinc-200 bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-600 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-200">
                                                        {{ $passkey['authenticator'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                                {{ __('Added :time', ['time' => $passkey['created_at_diff']]) }}
                                                @if ($passkey['last_used_at_diff'])
                                                    <span class="mx-1 opacity-50">/</span>
                                                    {{ __('Last used :time', ['time' => $passkey['last_used_at_diff']]) }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="confirmDelete({{ $passkey['id'] }})"
                                        class="rounded-md p-2 text-red-500 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/50"
                                        title="{{ __('Remove passkey') }}"
                                    >
                                        <x-phosphor-trash-duotone class="size-5" />
                                    </button>
                                </div>
                            @empty
                                <div class="p-8 text-center">
                                    <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-zinc-100 dark:bg-zinc-800">
                                        <x-phosphor-key-duotone class="size-7 text-zinc-400 dark:text-zinc-500" />
                                    </div>
                                    <p class="font-medium text-zinc-900 dark:text-zinc-100">{{ __('No passkeys yet') }}</p>
                                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Add a passkey to sign in without a password') }}</p>
                                </div>
                            @endforelse
                        </div>

                        <x-auth::elements.passkey-registration/>
                    </div>
                </section>
            @endif
            {{-- @end-chisel-passkeys --}}

        </x-app.settings-layout>

        @if ($showDeleteModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:keydown.escape="closeDeleteModal">
                <div class="fixed inset-0 bg-zinc-900/50" wire:click="closeDeleteModal"></div>

                <div class="relative w-full max-w-md rounded-lg border border-zinc-200 bg-white p-6 shadow-xl dark:border-zinc-700 dark:bg-zinc-900">
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100">{{ __('Remove passkey') }}</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Are you sure you want to remove the passkey ":name"? You will no longer be able to use it to sign in.', ['name' => $deletingPasskeyName]) }}
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-button type="button" color="gray" wire:click="closeDeleteModal">{{ __('Cancel') }}</x-button>
                        <x-button type="button" color="danger" wire:click="deletePasskey">{{ __('Remove passkey') }}</x-button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
