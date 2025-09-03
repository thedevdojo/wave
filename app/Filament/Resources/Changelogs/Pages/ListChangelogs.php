<?php

namespace App\Filament\Resources\Changelogs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Changelogs\ChangelogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChangelogs extends ListRecords
{
    protected static string $resource = ChangelogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
