<?php

namespace App\Filament\Resources\Changelogs\Pages;

use App\Filament\Resources\Changelogs\ChangelogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChangelog extends CreateRecord
{
    protected static string $resource = ChangelogResource::class;
}
