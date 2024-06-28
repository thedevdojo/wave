<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class Forms extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.forms';

    public function getHeader(): ?View
    {
        return view('filament.pages.forms-heading');
    }
}
