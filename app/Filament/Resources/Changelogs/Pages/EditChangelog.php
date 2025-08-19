<?php

namespace App\Filament\Resources\Changelogs\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Changelogs\ChangelogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChangelog extends EditRecord
{
    protected static string $resource = ChangelogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
