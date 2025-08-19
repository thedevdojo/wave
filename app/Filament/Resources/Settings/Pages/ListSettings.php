<?php

namespace App\Filament\Resources\Settings\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Cache;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function afterDelete(): void
    {
        Cache::forget('wave_settings');
    }
}
