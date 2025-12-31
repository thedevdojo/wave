<?php

use function Laravel\Folio\{middleware, name};
use Livewire\Volt\Component;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

middleware('auth');
name('settings.deletion');

new class extends Component
{
    public string $password = '';
    public bool $confirmDeletion = false;

    public function with(): array
    {
        $user = auth()->user();
        return [
            'isScheduled' => !is_null($user->deletion_scheduled_at),
            'scheduledDate' => $user->deletion_scheduled_at,
            'daysRemaining' => $user->deletion_scheduled_at 
                ? (int) ceil(now()->diffInDays($user->deletion_scheduled_at, false))
                : null,
        ];
    }

    public function scheduleAccountDeletion()
    {
        $this->validate([
            'password' => 'required',
            'confirmDeletion' => 'accepted',
        ], [
            'confirmDeletion.accepted' => 'You must confirm that you understand this action.',
        ]);

        // Verify password
        if (!Hash::check($this->password, auth()->user()->password)) {
            $this->addError('password', 'The password is incorrect.');
            return;
        }

        // Schedule deletion for 30 days from now
        $user = auth()->user();
        $user->deletion_scheduled_at = now()->addDays(30);
        $user->save();

        // Log account deletion scheduling
        ActivityLog::log('account_deletion_scheduled', 'Account deletion scheduled for 30 days from now', [
            'scheduled_date' => $user->deletion_scheduled_at->toDateTimeString()
        ]);

        // Reset form
        $this->password = '';
        $this->confirmDeletion = false;

        Notification::make()
            ->title('Account deletion scheduled')
            ->body('Your account will be permanently deleted in 30 days. You can cancel this at any time before then.')
            ->warning()
            ->send();
    }

    public function cancelAccountDeletion()
    {
        $user = auth()->user();
        $user->deletion_scheduled_at = null;
        $user->save();

        // Log account deletion cancellation
        ActivityLog::log('account_deletion_cancelled', 'Account deletion was cancelled');

        Notification::make()
            ->title('Account deletion cancelled')
            ->body('Your account will not be deleted. You can continue using your account normally.')
            ->success()
            ->send();
    }
};

?>

<x-layouts.app>
    @volt('settings.deletion')
        <div class="relative">
            <x-app.settings-layout
                title="Account Deletion"
                description="Permanently delete your account and all associated data.">
                
                <div class="w-full max-w-2xl space-y-6">
                    
                    @if($isScheduled)
                        <!-- Scheduled Deletion Warning -->
                        <x-card class="p-6 border-orange-200 bg-orange-50 dark:border-orange-800 dark:bg-orange-950/30">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-orange-900 dark:text-orange-100">Account Deletion Scheduled</h3>
                                    <p class="mt-2 text-sm text-orange-800 dark:text-orange-200">
                                        Your account is scheduled to be permanently deleted on 
                                        <strong>{{ $scheduledDate->format('F j, Y') }}</strong>
                                        ({{ abs($daysRemaining) }} {{ abs($daysRemaining) === 1 ? 'day' : 'days' }} remaining).
                                    </p>
                                    <p class="mt-2 text-sm text-orange-800 dark:text-orange-200">
                                        After this date, all your data including your profile, posts, and settings will be permanently removed and cannot be recovered.
                                    </p>
                                    <button
                                        wire:click="cancelAccountDeletion"
                                        wire:confirm="Are you sure you want to cancel the account deletion?"
                                        class="mt-4 px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg transition-colors">
                                        Cancel Deletion
                                    </button>
                                </div>
                            </div>
                        </x-card>
                    @else
                        <!-- Delete Account Form -->
                        <x-card class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-zinc-900">Delete Your Account</h3>
                                    <p class="mt-2 text-sm text-zinc-600">
                                        Once you delete your account, there is no going back. Please be certain.
                                    </p>
                                </div>

                                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <div class="flex gap-3">
                                        <svg class="flex-shrink-0 w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-red-900">Warning</h4>
                                            <div class="mt-2 text-sm text-red-800">
                                                <ul class="list-disc list-inside space-y-1">
                                                    <li>Your account will be scheduled for deletion in 30 days</li>
                                                    <li>All your personal data will be permanently removed</li>
                                                    <li>Your username will become available to others</li>
                                                    <li>Any active subscriptions will be cancelled</li>
                                                    <li>This action cannot be undone after the grace period</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form wire:submit="scheduleAccountDeletion" class="space-y-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">
                                            Confirm Your Password
                                        </label>
                                        <input 
                                            type="password" 
                                            id="password"
                                            wire:model="password"
                                            class="w-full px-3 py-2 border border-zinc-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            placeholder="Enter your password">
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input 
                                                type="checkbox" 
                                                id="confirmDeletion"
                                                wire:model="confirmDeletion"
                                                class="w-4 h-4 text-red-600 border-zinc-300 rounded focus:ring-red-500">
                                        </div>
                                        <label for="confirmDeletion" class="ml-3 text-sm text-zinc-700">
                                            I understand that this action will permanently delete my account and all associated data after 30 days.
                                        </label>
                                    </div>
                                    @error('confirmDeletion')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    <div class="pt-4 flex gap-3">
                                        <button 
                                            type="submit"
                                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove>Schedule Account Deletion</span>
                                            <span wire:loading>Processing...</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </x-card>

                        <!-- Additional Info -->
                        <x-card class="p-6 bg-blue-50 border-blue-200">
                            <div class="flex gap-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-900">Grace Period</h4>
                                    <p class="mt-1 text-sm text-blue-800">
                                        You'll have 30 days to cancel the deletion if you change your mind. During this time, you can still log in and use your account normally. After 30 days, your account and all data will be permanently deleted.
                                    </p>
                                </div>
                            </div>
                        </x-card>
                    @endif

                </div>

            </x-app.settings-layout>
        </div>
    @endvolt
</x-layouts.app>
