<?php

namespace App\Http\Livewire\Wave;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DeployToDo extends Component
{
    public $api_key;
    public $repo;

    public function deploy(){

        $deployJSONStr = file_get_contents(base_path('deploy.json'));
        $deployJSON = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $deployJSONStr), true);

        // repo must contain a '/', do a check for that
        $repoSplit = explode('/', $this->repo);
        $repoName = (isset($repoSplit[0]) && isset($repoSplit[1])) ? $repoSplit[0] . '-' . $repoSplit[1] : false;
        if(!$repoName){
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Please make sure you enter a valiid repo (ex: user/repo)']);
            return;
        }

        if(empty($this->api_key)){
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'C\'mon, you can\'t leave the API key field empty.']);
            return;
        }

        if(is_null($deployJSON)){
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Sorry it looks like your deploy.json does not contain valid JSON']);
            return;
        }

        // replace values with repoName and Repo url
        $finalJSONPayload = json_encode($deployJSON);
        $finalJSONPayload = str_replace('${wave.name}', $repoName, $finalJSONPayload);
        $finalJSONPayload = str_replace('${wave.repo}', $this->repo, $finalJSONPayload);

        $response = Http::withToken($this->api_key)->withBody( $finalJSONPayload, 'application/json')
            ->post('https://api.digitalocean.com/v2/apps');

        // if the response is not successful, display the message back from DigitalOcean
        if(!$response->successful()){
            $responseBody = json_decode($response->body(), true);
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $responseBody['message']]);
            return;

        }

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Successfully deployed your application!']);
        //dd('hit');
    }

    public function render()
    {
        return view('livewire.wave.deploy-to-do');
    }
}
