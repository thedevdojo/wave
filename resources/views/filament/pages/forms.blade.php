<?php

use Livewire\Volt\Component;
use function Laravel\Folio\{name};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

name('forms-builder');

new class extends Component implements HasForms {

    use InteractsWithForms;
    
    public ?array $data = [];

    public function mount(){
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('Form Title')
                    ->rules('required|string'),
                \Filament\Forms\Components\TextInput::make('name')
                    ->label('Name (slug)')
                    ->rules('required|string'),
                \Filament\Forms\Components\RichEditor::make('description')
                    ->label('Description')
                    ->rules('required|string'),
            ])
            ->statePath('data');
    }
}; ?>

<x-filament-panels::page class="overflow-hidden forms-page">
    @volt('forms-builder')
        <div class="w-full h-full">
            <div class="flex items-stretch w-full h-full">
                <div class="absolute top-0 flex-shrink-0 pt-16 w-full max-w-sm h-screen bg-white">
                    <div x-data="{ open: false }" class="relative">
                        <div x-on:click="open=!open" class="p-3 border-b">
                            Form Information
                        </div>
                        <div x-show="open" class="relative p-5 border-b" x-collapse>
                            <form wire:submit="save">
                                {{ $this->form }}
                                <x-button type="submit" class="mt-5">Save</x-button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="relative flex-1 ml-[24rem] w-full h-full bg-blue-200">
                    Adding some data here...
                </div>
            </div>
        </div>
    @endvolt
</x-filament-panels::page>


