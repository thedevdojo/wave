<?php

namespace App\Filament\Resources\Forms\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\Forms\FormsResource;
use Filament\Actions;
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
        // $fields = [];

        if (is_string($data['fields'])) {
            $data['fields'] = json_decode($data['fields'], true);
        }

        // dd($data['fields']);

        // foreach($data['fields'] as $field){
        //     $fields[] = json_decode($field, true);
        // }

        // $data['fields'] = $fields;

        // dd($data['fields']);

        return $data;
        // Runs before the form fields are populated with their default values.
    }
}
