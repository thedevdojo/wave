<?php

namespace Wave\Widgets;

use Filament\Widgets\Widget;

class AnalyticsPlaceholderWidget extends Widget
{
    protected static ?int $sort = -2;

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'wave::widgets.analytics-placeholder-widget';
}
