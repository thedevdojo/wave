<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Support\Enums\Width;
use Filament\Pages\Page;

class Media extends Page
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';

    protected string $view = 'wave::media.index';

    protected static ?int $navigationSort = 5;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }
}
