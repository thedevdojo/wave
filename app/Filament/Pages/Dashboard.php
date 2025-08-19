<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Panel;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'phosphor-house-duotone';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->pages([]);
    }
}
