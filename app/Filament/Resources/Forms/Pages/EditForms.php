<?php

namespace App\Filament\Resources\Forms\Pages;

use App\Filament\Resources\Forms\FormsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditForms extends EditRecord
{
    protected static string $resource = FormsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // forms created before the fix stored fields as a JSON string inside the JSON column
        if (is_string($data['fields'])) {
            $data['fields'] = json_decode($data['fields'], true);
        }

        return $data;
    }
}
