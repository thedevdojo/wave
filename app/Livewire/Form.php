<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form as FilamentForm;
use Filament\Notifications\Notification;
use Livewire\Component;
use Wave\Traits\HasDynamicFields;
use App\Models\Forms as FormModel;

class Form extends Component implements HasForms
{
    use InteractsWithForms, HasDynamicFields;
    public $formModel; 
    public ?array $data = [];
    public function mount($name)
    {
        $this->formModel = FormModel::where('slug', $name)->first();
        $this->form->fill();
    }


    public function form(FilamentForm $form): FilamentForm
    {
        return $form
            ->schema([
                ...$this->dynamicFields( $this->formModel->fields )
            ])
            ->statePath('data');
    }

    public function save(){

        $state = $this->form->getState();
        $this->validate();

        
		$this->saveDynamicFields($this->formModel->fields, $this->formModel);

        Notification::make()
            ->title('Successfully saved your profile settings')
            ->success()
            ->send();
    }

    public function render()
    {
        return view('livewire.form');
    }
}
