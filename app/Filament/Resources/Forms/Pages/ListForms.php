<?php

namespace App\Filament\Resources\Forms\Pages;

use App\Filament\Resources\Forms\FormsResource;
use Filament\Actions\CreateAction;
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
