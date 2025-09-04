<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\DashboardWidget;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
// use Filament\Widgets;
// use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wave\Widgets;

class AdminPanelProvider extends PanelProvider
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-presentation-chart-line';
    }

    public function panel(Panel $panel): Panel
    {

        Blade::component('wave::admin.components.label', 'label');

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            // ->discoverWidgets(in: app_path('BezhanSalleh\FilamentGoogleAnalytics\Widgets'), for: 'BezhanSalleh\\FilamentGoogleAnalytics\\Widgets')
            ->widgets([
                DashboardWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                // \App\Http\Middleware\WaveEditTab::class
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandLogo(fn () => view('wave::admin.logo'))
            ->darkModeBrandLogo(fn () => view('wave::admin.logo-dark'));
    }
}
