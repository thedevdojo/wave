<?php

namespace App\Filament\Resources\ChangelogResource\Pages;

use App\Filament\Resources\ChangelogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChangelog extends CreateRecord
{
    protected static string $resource = ChangelogResource::class;
}
