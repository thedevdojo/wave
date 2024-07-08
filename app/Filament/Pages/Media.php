<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;


class Media extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static string $view = 'wave::media.index';
    
    protected static ?int $navigationSort = 5;
 
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

}
