<?php

namespace Wave\Traits;

use Illuminate\Support\Str;

trait HasDynamicFields
{
    private function dynamicFields($fields){
        $dynamicFields = [];
        foreach($fields as $field){

            $key = Str::slug($field['label']);

            if(!class_exists($field['type'])){
                $fieldType = '\Filament\Forms\Components\\' . $field['type'];
            } else {
                $fieldType = $field['type'];
            }

            

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

            $keyValue = auth()->user()->profileKeyValues->where('key', $key)->first();
            
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
        foreach($fields as $field){
            $key = Str::slug($field['label']);

            if(isset($state[$key])){
                $value = $state[$key];
                if (is_array($state[$key])) {
                    $value = json_encode($state[$key]);
                }
                auth()->user()->setProfileKeyValue($key, $value, $field['type']);
            }
        }
    }
}