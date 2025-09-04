<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardWidget extends Widget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.dashboard-widget';
}
