<?php

namespace App\Filament\Resources\SettingResource\Pages;

use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;

    protected function afterCreate(): void
    {
        Cache::forget('wave_settings');
    }
}
