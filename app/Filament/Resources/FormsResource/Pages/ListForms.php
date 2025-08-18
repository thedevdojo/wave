<?php

namespace App\Filament\Resources\FormsResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FormsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForms extends ListRecords
{
    protected static string $resource = FormsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
