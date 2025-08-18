<?php

namespace App\Filament\Resources\Permissions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
