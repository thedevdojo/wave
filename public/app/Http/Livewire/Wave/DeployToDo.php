<?php

namespace App\Http\Livewire\Wave;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DeployToDo extends Component
{
    public $api_key;
    public $repo;
    public $deploy;
    public $app_id;
    public $deployments;
    public $app;

    public function mount(){
        // get the deploy.json file and convert to object
        $this->deploy = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents(base_path('deploy.json')) ), true);
        $this->checkForAppDeployment();
    }

    private function checkForAppDeployment(){
        if(isset( $this->deploy['wave'] ) && isset( $this->deploy['wave']['app_id'] )){
            $this->app_id = $this->deploy['wave']['app_id'];
            $this->api_key = $this->deploy['wave']['api_key'];
            $this->deployments = $this->getDeployments();
            $this->app = $this->getAppInfo();
        }
    }

    public function getDeployments(){
        $response = Http::withToken($this->api_key)->get('https://api.digitalocean.com/v2/apps/' . $this->app_id . '/deployments');

        return json_decode($response->body(), true);
    }

    public function getAppInfo(){
        $response = Http::withToken($this->api_key)->get('https://api.digitalocean.com/v2/apps/' . $this->app_id);

        return json_decode($response->body(), true);
    }

    private function writeToDeployFile($id, $key, $deployFileArray){
        $deployFileArray['wave']['app_id'] = $id;
        $deployFileArray['wave']['api_key'] = $key;


        file_put_contents(base_path('deploy.json'), stripslashes(json_encode($deployFileArray, JSON_PRETTY_PRINT)));
        $this->deploy = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', file_get_contents(base_path('deploy.json')) ), true);
    }

    public function deploy(){

        if(!isset($this->app_id)){
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

            if(is_null($this->deploy)){
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Sorry it looks like your deploy.json does not contain valid JSON']);
                return;
            }

            // replace values with repoName and Repo url
            $finalJSONPayload = json_encode($this->deploy);
            $finalJSONPayload = str_replace('${wave.name}', str_replace('_', '-', $repoName), $finalJSONPayload);
            //dd($this->repo);
            $finalJSONPayload = str_replace('${wave.repo}', $this->repo, $finalJSONPayload);

            $response = Http::withToken($this->api_key)->withBody( $finalJSONPayload, 'application/json')
                ->post('https://api.digitalocean.com/v2/apps');

            // if the response is not successful, display the message back from DigitalOcean
            if(!$response->successful()){
                $responseBody = json_decode($response->body(), true);
                $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => $responseBody['message']]);
                return;

            }

            // get app ID and set it in the JSON
            $responseBody = json_decode($response->body(), true);

            $this->writeToDeployFile($responseBody['app']['id'], $this->api_key, $this->deploy);

            $this->checkForAppDeployment();

            $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Successfully deployed your application!']);
            //dd('hit');
        }
    }

    public function render()
    {
        return view('livewire.wave.deploy-to-do');
    }
}
