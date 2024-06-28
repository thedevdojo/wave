<?php

namespace Wave\Traits;

trait HasDynamicFields
{
    private function dynamicFields(){
        $dynamicFields = [];
        foreach(config('profile.fields') as $key => $field){
            $fieldType = '\Filament\Forms\Components\\' . $field['type'];
            $newField = $fieldType::make($key);
            
            if(isset($field['label'])){
                $newField->label($field['label']);
            }

            if(isset($field['options'])){
                $newField->options( $field['options'] );
            }

            if(isset($field['suggestions'])){
                $newField->suggestions( $field['suggestions'] );
            }

            if(isset($field['rules'])){
                $newField->rules( $field['rules'] );
            }

            $keyValue = auth()->user()->keyValues->where('key', $key)->first();
            
            $value = $keyValue->value ?? '';
            if (!empty($value)) {
                if (json_decode($value, true) !== null) {
                    $value = json_decode($value, true);
                }
            }

            $newField->default($value);
            // add validation

            $dynamicFields[] = $newField;
        }

        return $dynamicFields;
    }

    private function saveDynamicFields($fields){
        $state = $this->form->getState();
        foreach($fields as $key => $field){
            if(isset($state[$key])){
                $value = $state[$key];
                if (is_array($state[$key])) {
                    $value = json_encode($state[$key]);
                }
                auth()->user()->setKeyValue($key, $value, $field['type']);
            }
        }
    }
}