<?php

namespace App\Filament\Resources\PlanResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlan extends EditRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
