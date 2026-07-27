<?php

use App\Models\Organization;
use App\Models\Invitation;
use App\Models\User;
use App\Notifications\OrganizationInvitation;
use Livewire\Volt\Component;
use function Laravel\Folio\{middleware, name};
use Filament\Notifications\Notification;

middleware('auth');
name('settings.organization');

new class extends Component
{
    public string $name = '';
    public string $inviteEmail = '';

    public function createOrganization(): void
    {
        $this->validate([
            'name' => 'required|string|min:3|max:255',
        ]);

        // Double check if user is already in an organization
        if (auth()->user()->organization_id !== null || auth()->user()->ownedOrganization()->exists()) {
            Notification::make()
                ->title('You are already part of an organization.')
                ->danger()
                ->send();
            return;
        }

        $organization = Organization::create([
            'name' => $this->name,
            'user_id' => auth()->id(),
        ]);

        auth()->user()->update([
            'organization_id' => $organization->id,
        ]);

        $this->name = '';

        Notification::make()
            ->title('Organization created successfully!')
            ->success()
            ->send();
    }

    public function inviteUser(): void
    {
        $this->validate([
            'inviteEmail' => 'required|email',
        ]);

        $organization = auth()->user()->ownedOrganization;

        if (!$organization) {
            Notification::make()
                ->title('Only organization admins can invite users.')
                ->danger()
                ->send();
            return;
        }

        // Find user by email
        $targetUser = User::where('email', $this->inviteEmail)->first();

        if (!$targetUser) {
            $this->addError('inviteEmail', 'This user is not registered on the platform. They must sign up first before you can send a join request.');
            return;
        }

        // Check if user is already in an organization
        if ($targetUser->organization_id !== null || $targetUser->ownedOrganization()->exists()) {
            $this->addError('inviteEmail', 'This user is already a member of an organization.');
            return;
        }

        // Check if invitation already exists
        $invitationExists = Invitation::where('organization_id', $organization->id)
            ->where('email', $this->inviteEmail)
            ->exists();

        if ($invitationExists) {
            $this->addError('inviteEmail', 'An invitation has already been sent to this user.');
            return;
        }

        // Create the invitation
        $invitation = Invitation::create([
            'organization_id' => $organization->id,
            'email' => $this->inviteEmail,
        ]);

        // Send notification
        $targetUser->notify(new OrganizationInvitation($invitation, auth()->user(), $organization));

        $this->inviteEmail = '';

        Notification::make()
            ->title('Invitation sent successfully!')
            ->success()
            ->send();
    }

    public function cancelInvitation($invitationId): void
    {
        $organization = auth()->user()->ownedOrganization;

        if (!$organization) {
            return;
        }

        $invitation = Invitation::where('id', $invitationId)
            ->where('organization_id', $organization->id)
            ->first();

        if ($invitation) {
            $invitation->delete();
            Notification::make()
                ->title('Invitation cancelled.')
                ->success()
                ->send();
        }
    }

    public function removeMember($memberId): void
    {
        $organization = auth()->user()->ownedOrganization;

        if (!$organization) {
            return;
        }

        if ($memberId == auth()->id()) {
            Notification::make()
                ->title('You cannot remove yourself from your own organization.')
                ->danger()
                ->send();
            return;
        }

        $member = User::where('id', $memberId)
            ->where('organization_id', $organization->id)
            ->first();

        if ($member) {
            $member->update(['organization_id' => null]);
            Notification::make()
                ->title('Member removed successfully.')
                ->success()
                ->send();
        }
    }

    public function leaveOrganization(): void
    {
        $user = auth()->user();

        if ($user->isOrganizationAdmin()) {
            Notification::make()
                ->title('Organization admins cannot leave. You must disband or transfer ownership.')
                ->danger()
                ->send();
            return;
        }

        if ($user->organization_id !== null) {
            $user->update(['organization_id' => null]);
            Notification::make()
                ->title('You have successfully left the organization.')
                ->success()
                ->send();
        }
    }
}
?>

<x-layouts.app>
    @volt('settings.organization')
        <div class="relative">
            <x-app.settings-layout
                title="Organization Settings"
                description="Manage your organization, members, and invitations."
            >
                @if(!auth()->user()->organization_id && !auth()->user()->isOrganizationAdmin())
                    <!-- Create Organization State -->
                    <div class="max-w-xl space-y-6">
                        <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                            <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Create an Organization</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
                                You do not currently belong to any organization. Enter a name below to create your organization and invite others to join.
                            </p>

                            <form wire:submit.prevent="createOrganization" class="space-y-4">
                                <div>
                                    <label for="org_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Organization Name</label>
                                    <input
                                        type="text"
                                        id="org_name"
                                        wire:model="name"
                                        placeholder="e.g. Acme Corp"
                                        class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm shadow-sm"
                                        required
                                    >
                                    @error('name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end pt-2">
                                    <x-button type="submit">Create Organization</x-button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    @php
                        $org = auth()->user()->organization ?? auth()->user()->ownedOrganization;
                    @endphp

                    @if(auth()->user()->isOrganizationAdmin())
                        <!-- Admin Management View -->
                        <div class="space-y-8 max-w-2xl">
                            <!-- Organization Info -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <span class="px-2 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded dark:bg-blue-900/20 dark:text-blue-400 uppercase tracking-wider mb-2 inline-block">Administrator</span>
                                <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $org->name }}</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                    You created and manage this organization.
                                </p>
                            </div>

                            <!-- Invite Users -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Invite Members</h4>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                                    Enter the email address of a registered user to invite them to your organization.
                                </p>

                                <form wire:submit.prevent="inviteUser" class="flex gap-3">
                                    <div class="flex-1">
                                        <input
                                            type="email"
                                            wire:model="inviteEmail"
                                            placeholder="user@example.com"
                                            class="w-full px-3 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 dark:bg-zinc-950 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm shadow-sm"
                                            required
                                        >
                                    </div>
                                    <x-button type="submit">Invite</x-button>
                                </form>
                                @error('inviteEmail')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Current Members -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Organization Members</h4>
                                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    @foreach($org->members as $member)
                                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $member->avatar() }}" class="w-10 h-10 rounded-full border border-zinc-200 dark:border-zinc-700">
                                                <div>
                                                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $member->name }}</p>
                                                    <p class="text-xs text-zinc-500">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                @if($member->id === auth()->id())
                                                    <span class="text-xs text-zinc-400 italic">Owner</span>
                                                @else
                                                    <button
                                                        type="button"
                                                        wire:click="removeMember({{ $member->id }})"
                                                        wire:confirm="Are you sure you want to remove this member from your organization?"
                                                        class="text-xs text-red-600 hover:text-red-800 font-medium cursor-pointer hover:underline"
                                                    >
                                                        Remove
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pending Invitations -->
                            @if($org->invitations->count() > 0)
                                <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                    <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Pending Invitations</h4>
                                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                        @foreach($org->invitations as $invitation)
                                            <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                                <div class="flex flex-col">
                                                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $invitation->email }}</p>
                                                    <p class="text-xs text-zinc-500">Sent on {{ $invitation->created_at->format('M d, Y h:i A') }}</p>
                                                </div>
                                                <div>
                                                    <button
                                                        type="button"
                                                        wire:click="cancelInvitation('{{ $invitation->id }}')"
                                                        class="text-xs text-zinc-500 hover:text-zinc-700 font-medium cursor-pointer hover:underline"
                                                    >
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Member View -->
                        <div class="space-y-8 max-w-2xl">
                            <!-- Organization Info -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <span class="px-2 py-1 text-xs font-semibold bg-zinc-100 text-zinc-700 rounded dark:bg-zinc-800 dark:text-zinc-300 uppercase tracking-wider mb-2 inline-block">Member</span>
                                <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">{{ $org->name }}</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                                    You are a member of this organization.
                                </p>
                            </div>

                            <!-- Admin Info -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Organization Admin</h4>
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $org->admin->avatar() }}" class="w-12 h-12 rounded-full border border-zinc-200 dark:border-zinc-700">
                                    <div>
                                        <p class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $org->admin->name }}</p>
                                        <p class="text-xs text-zinc-500">{{ $org->admin->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- List of Members -->
                            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">
                                <h4 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-4">All Members</h4>
                                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                    @foreach($org->members as $member)
                                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                                            <div class="flex items-center space-x-3">
                                                <img src="{{ $member->avatar() }}" class="w-10 h-10 rounded-full border border-zinc-200 dark:border-zinc-700">
                                                <div>
                                                    <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">{{ $member->name }}</p>
                                                    <p class="text-xs text-zinc-500">{{ $member->email }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                @if($member->id === $org->user_id)
                                                    <span class="text-xs text-zinc-400 italic">Owner</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Leave Organization Button -->
                            <div class="p-6 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900 rounded-xl shadow-sm flex items-center justify-between">
                                <div>
                                    <h4 class="text-base font-semibold text-red-900 dark:text-red-400">Leave Organization</h4>
                                    <p class="text-xs text-red-700 dark:text-red-500 mt-1">
                                        You will lose access to this organization and its features.
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    wire:click="leaveOrganization"
                                    wire:confirm="Are you sure you want to leave this organization?"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow cursor-pointer"
                                >
                                    Leave
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
