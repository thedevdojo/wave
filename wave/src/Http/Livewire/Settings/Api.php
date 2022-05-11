<?php

namespace Wave\Http\Livewire\Settings;

use Illuminate\Support\Str;
use Livewire\Component;
use Wave\ApiKey;

class Api extends Component
{
    // variables for (b)rowing keys
    public $keys = [];

    // variables for (r)eading/viewing a key
    public $readKey;
    public $readModal = false;

    // variables for (a)dding keys
    public $key_name;

    // variables for (e)diting keys
    public $editKey = '';
    public $editModal = false;

    // variables for (d)diting keys
    public $deleteKey = '';
    public $deleteModal = false;

    public function mount(){
        $this->refreshKeys();
    }

    protected function rules()
    {
        return [
            'key_name' => 'required',
            'editKey.name' => 'required'
        ];
    }

    public function readApiModal(ApiKey $key){
        $this->readKey = $key;
        $this->readModal = true;
    }

    public function editApiModal(ApiKey $key){
        $this->editKey = $key;
        $this->editModal = true;
    }

    public function edit(){
        $this->validateOnly('editKey.name');

        // For security reasons we need to be sure that the user editing the key name is the owner of the API key
        if($this->editKey->user_id != auth()->user()->id){
            // Display danger toast notification
            $this->dispatchBrowserEvent('popToast', ['type' => 'danger', 'message' => 'Cannot update key name. Invalid User']);
        } else {
            // Update the key name and display success message
            $this->editKey->save();
            $this->dispatchBrowserEvent('popToast', ['type' => 'success', 'message' => 'Successfully update API Key name.']);
        }
        $this->editModal = false;
        $this->editKey = null;

        $this->refreshKeys();
    }

    public function add(){

        $this->validateOnly('key_name');

        $apiKey = auth()->user()->createApiKey(Str::slug($this->key_name));

        // Display success toast notification
        $this->dispatchBrowserEvent('popToast', ['type' => 'success', 'message' => 'Successfully created new API Key']);

        // Clear the API Key name
        $this->key_name = '';

        $this->refreshKeys();
    }

    public function deleteApiModal(ApiKey $key){
        $this->deleteKey = $key;
        $this->deleteModal = true;
    }

    public function delete(){

        // For security reasons we need to make sure that the user deleting the API key is the owner of the API key
        if($this->deleteKey->user_id != auth()->user()->id){
            // Display danger toast notification
            $this->dispatchBrowserEvent('popToast', ['type' => 'danger', 'message' => 'Cannot delete Key. Invalid User']);
        } else {
            // Delete the API key and show success message
            $this->deleteKey->delete();
            $this->dispatchBrowserEvent('popToast', ['type' => 'success', 'message' => 'Successfully Deleted API Key']);
        }

        $this->deleteKey = null;
        $this->refreshKeys();
    }

    public function refreshKeys(){
        $this->keys = auth()->user()->apiKeys;
    }

    public function render()
    {
        return view('theme::livewire.settings.api');
    }
}
