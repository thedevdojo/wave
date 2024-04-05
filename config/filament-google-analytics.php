<?php

return [

    /**
     * Dashboard Page
     */
    'dedicated_dashboard' => true,
    'dashboard_icon' => 'heroicon-m-chart-bar',

    /**
     * Widgets
     */
    'page_views' => [
        'filament_dashboard' => false,
        'global' => true,
    ],
    'visitors' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'active_users_one_day' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'active_users_seven_day' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'active_users_twenty_eight_day' => [
        'filament_dashboard' => false,
        'global' => false,
    ],

    'sessions' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'sessions_duration' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'sessions_by_country' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'sessions_by_device' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'most_visited_pages' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    'top_referrers_list' => [
        'filament_dashboard' => false,
        'global' => true,
    ],

    /**
     * Trajectory Icons
     */
    'trending_up_icon' => 'heroicon-o-arrow-trending-up',
    'trending_down_icon' => 'heroicon-o-arrow-trending-down',
    'steady_icon' => 'heroicon-o-arrows-right-left',

    /**
     * Trajectory Colors
     */
    'trending_up_color' => 'success',
    'trending_down_color' => 'danger',
    'steady_color' => 'secondary',
];
