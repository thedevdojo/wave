<?php

namespace Wave\Widgets;

use Filament\Widgets\Widget;

class PostsPagesWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'wave::widgets.posts-pages-widget';
}
