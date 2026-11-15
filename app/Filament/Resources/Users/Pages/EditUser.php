<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\EditRecord;
use Wave\Filament\Actions\DeleteUserAction;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteUserAction::make(),
        ];
    }

    // The roles select syncs the pivot directly, so Spatie's role events don't fire.
    protected function afterSave(): void
    {
        $this->record->clearUserCache();
    }
}
