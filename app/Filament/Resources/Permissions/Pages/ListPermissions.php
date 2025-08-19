<?php

namespace App\Filament\Resources\Permissions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
