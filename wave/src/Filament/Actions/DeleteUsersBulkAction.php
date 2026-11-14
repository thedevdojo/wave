<?php

namespace Wave\Filament\Actions;

use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Collection;
use Wave\Actions\Accounts\DeleteUser;

class DeleteUsersBulkAction extends DeleteBulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalDescription('Active billing subscriptions will be cancelled immediately before each account is deleted. Accounts whose cancellation fails will be retained.');
        $this->using(function (DeleteBulkAction $action, Collection $records): void {
            foreach ($records as $record) {
                if (! app(DeleteUser::class)($record)) {
                    $action->reportBulkProcessingFailure();
                }
            }
        });
        $this->failureNotificationBody('Accounts that could not be fully processed were retained. Some subscriptions may already be cancelled; review billing before retrying.');
    }
}
