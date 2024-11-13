<?php

namespace App\Filament\Resources\SettingResource\Pages;

use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function afterDelete(): void
    {
        Cache::forget('wave_settings');
    }
}
