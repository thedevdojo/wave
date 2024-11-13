<?php

namespace App\Filament\Resources\SettingResource\Pages;

use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        Cache::forget('wave_settings');
    }
}
