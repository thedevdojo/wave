<?php

namespace Wave\Http\Livewire\Settings;

use Livewire\Component;

class Security extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'current_password' => 'required|password',
            'password' => 'required|confirmed|min:'.config('wave.auth.min_password_length')
        ];
    }

    public function save(){
        $this->validate();

        auth()->user()->forceFill([
            'password' => bcrypt($this->password)
        ])->save();

        // Display success toast notification
        $this->dispatchBrowserEvent('popToast', ['type' => 'success', 'message' => 'Successfully updated your password']);

        // Clear the input fields
        $this->current_password = $this->password = $this->password_confirmation = "";

    }

    public function render()
    {
        return view('theme::livewire.settings.security');
    }
}
