<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
// use Filament\Widgets;
// use BezhanSalleh\FilamentGoogleAnalytics\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wave\Widgets;

class AdminPanelProvider extends PanelProvider
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-presentation-chart-line';
    }

    private $dynamicWidgets = [];

    public function panel(Panel $panel): Panel
    {
        $this->renderAnalyticsIfCredentialsExist();

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
                Widgets\WaveInfoWidget::class,
                Widgets\WelcomeWidget::class,
                Widgets\UsersWidget::class,
                Widgets\PostsPagesWidget::class,
                ...$this->dynamicWidgets,

                // Google Analytics Widgets that are available here: https://filamentphp.com/plugins/bezhansalleh-google-analytics
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\PageViewsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\VisitorsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersOneDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersSevenDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\ActiveUsersTwentyEightDayWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsDurationWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByCountryWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\SessionsByDeviceWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\MostVisitedPagesWidget::class,
                \BezhanSalleh\FilamentGoogleAnalytics\Widgets\TopReferrersListWidget::class,
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

    // This function will render if user has account crenditals file
    // located at storage/app/analytics/service-account-credentials.json
    // Find More details here: https://github.com/spatie/laravel-analytics
    private function renderAnalyticsIfCredentialsExist()
    {
        if (file_exists(storage_path('app/analytics/service-account-credentials.json'))) {
            \Config::set('filament-google-analytics.page_views.filament_dashboard', true);
            \Config::set('filament-google-analytics.active_users_one_day.filament_dashboard', true);
            \Config::set('filament-google-analytics.active_users_seven_day.filament_dashboard', true);
            \Config::set('filament-google-analytics.active_users_twenty_eight_day.filament_dashboard', true);
            \Config::set('filament-google-analytics.most_visited_pages.filament_dashboard', true);
            \Config::set('filament-google-analytics.top_referrers_list.filament_dashboard', true);
        } else {
            $this->dynamicWidgets = [Widgets\AnalyticsPlaceholderWidget::class];
        }
    }
}
