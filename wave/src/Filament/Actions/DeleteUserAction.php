<?php

namespace Wave\Filament\Actions;

use App\Models\User;
use Filament\Actions\DeleteAction;
use Wave\Actions\Accounts\DeleteUser;

class DeleteUserAction extends DeleteAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalDescription('Active billing subscriptions will be cancelled immediately before this account is deleted.');
        $this->using(fn (User $record): bool => app(DeleteUser::class)($record));
        $this->failureNotificationTitle('User was not deleted');
        $this->failureNotificationBody('Billing cancellation or account deletion could not be completed. The account was retained. Some subscriptions may already be cancelled; review billing before retrying.');
    }
}
