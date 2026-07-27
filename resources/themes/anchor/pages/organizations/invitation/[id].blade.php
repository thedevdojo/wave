<?php

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\OrganizationJoined;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};
use Filament\Notifications\Notification;

middleware('auth');
name('organizations.invitation');

new class extends Component
{
    public $id;
    public $invitation;

    public function mount(): void
    {
        $this->invitation = Invitation::with(['organization.admin'])->find($this->id);

        if (!$this->invitation) {
            abort(404, 'Invitation not found');
        }

        if (auth()->user()->email !== $this->invitation->email) {
            Notification::make()
                ->title('This invitation was sent to a different email address.')
                ->danger()
                ->send();
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    public function accept()
    {
        // Refresh invitation status
        $invitation = Invitation::find($this->id);
        if (!$invitation) {
            Notification::make()
                ->title('This invitation is no longer active.')
                ->danger()
                ->send();
            return $this->redirect(route('dashboard'), navigate: true);
        }

        $user = auth()->user();

        // Check if user is already in an organization
        if ($user->organization_id !== null || $user->ownedOrganization()->exists()) {
            Notification::make()
                ->title('You are already a member or administrator of an organization. You must leave or transfer ownership first.')
                ->danger()
                ->send();
            return;
        }

        // Set user's organization
        $user->update([
            'organization_id' => $invitation->organization_id
        ]);

        // Send notification to organization admin
        $org = $invitation->organization;
        $admin = $org->admin;
        $admin->notify(new OrganizationJoined($user, $org));

        // Delete invitation
        $invitation->delete();

        Notification::make()
            ->title('Successfully joined ' . $org->name . '!')
            ->success()
            ->send();

        return $this->redirect(route('settings.organization'), navigate: true);
    }

    public function decline()
    {
        $invitation = Invitation::find($this->id);

        if ($invitation) {
            $invitation->delete();
        }

        Notification::make()
            ->title('Invitation declined.')
            ->info()
            ->send();

        return $this->redirect(route('dashboard'), navigate: true);
    }
}
?>

<x-layouts.app>
    @volt('organizations.invitation')
        <x-app.container>
            <div class="max-w-md mx-auto my-12">
                <x-card class="p-8 text-center flex flex-col items-center">
                    <!-- Icon / Avatar -->
                    <div class="relative w-20 h-20 mb-6">
                        @if($this->invitation && $this->invitation->organization && $this->invitation->organization->admin)
                            <img src="{{ $this->invitation->organization->admin->avatar() }}" class="w-20 h-20 rounded-full border-4 border-zinc-200 dark:border-zinc-800 shadow-md">
                        @endif
                        <div class="absolute -bottom-1 -right-1 bg-blue-500 text-white rounded-full p-1.5 shadow">
                            <x-phosphor-users-duotone class="w-4 h-4" />
                        </div>
                    </div>

                    <!-- Heading -->
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-2">
                        Organization Invitation
                    </h2>

                    @if($this->invitation && $this->invitation->organization && $this->invitation->organization->admin)
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-6">
                            <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $this->invitation->organization->admin->name }}</span>
                            has invited you to join their organization
                            <span class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $this->invitation->organization->name }}</span>.
                        </p>

                        <!-- Info Box -->
                        <div class="w-full bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-lg p-4 mb-8 text-left text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Organization:</span>
                                <span class="font-medium text-zinc-950 dark:text-zinc-50">{{ $this->invitation->organization->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Invited By:</span>
                                <span class="font-medium text-zinc-950 dark:text-zinc-50">{{ $this->invitation->organization->admin->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-500">Admin Email:</span>
                                <span class="font-medium text-zinc-950 dark:text-zinc-50">{{ $this->invitation->organization->admin->email }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex flex-col w-full space-y-3">
                        <button
                            type="button"
                            wire:click="accept"
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition cursor-pointer"
                        >
                            Accept Invitation
                        </button>
                        <button
                            type="button"
                            wire:click="decline"
                            class="w-full py-2.5 bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 font-semibold rounded-lg transition cursor-pointer"
                        >
                            Decline
                        </button>
                    </div>
                </x-card>
            </div>
        </x-app.container>
    @endvolt
</x-layouts.app>
