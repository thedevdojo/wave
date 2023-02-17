<?php

namespace DevDojo\Themes\Http\Controllers;

use Voyager;
use Illuminate\Http\Request;
use \DevDojo\Themes\Models\Theme;
use Illuminate\Support\Facades\File;
use \DevDojo\Themes\Models\ThemeOptions;
use TCG\Voyager\Http\Controllers\Controller;

class ThemesController extends Controller
{
    private $themes_folder = '';

    public function __construct(){
        $this->themes_folder = config('themes.themes_folder', resource_path('views/themes'));
    }

    public function index(){

        // Anytime the admin visits the theme page we will check if we
        // need to add any more themes to the database
        $this->installThemes();
        $themes = Theme::all();

        return view('themes::index', compact('themes'));
    }

    private function getThemesFromFolder(){
        $themes = array();

        if(!file_exists($this->themes_folder)){
            mkdir($this->themes_folder);
        }

        $scandirectory = scandir($this->themes_folder);

        if(isset($scandirectory)){

            foreach($scandirectory as $folder){
                //dd($theme_folder . '/' . $folder . '/' . $folder . '.json');
                $json_file = $this->themes_folder . '/' . $folder . '/' . $folder . '.json';
                if(file_exists($json_file)){
                    $themes[$folder] = json_decode(file_get_contents($json_file), true);
                    $themes[$folder]['folder'] = $folder;
                    $themes[$folder] = (object)$themes[$folder];
                }
            }

        }

        return (object)$themes;
    }

    private function installThemes() {

        $themes = $this->getThemesFromFolder();

        foreach($themes as $theme){
            if(isset($theme->folder)){
                $theme_exists = Theme::where('folder', '=', $theme->folder)->first();
                // If the theme does not exist in the database, then update it.
                if(!isset($theme_exists->id)){
                    $version = isset($theme->version) ? $theme->version : '';
                    Theme::create(['name' => $theme->name, 'folder' => $theme->folder, 'version' => $version]);
                    if(config('themes.publish_assets', true)){
                        $this->publishAssets($theme->folder);
                    }
                } else {
                    // If it does exist, let's make sure it's been updated
                    $theme_exists->name = $theme->name;
                    $theme_exists->version = isset($theme->version) ? $theme->version : '';
                    $theme_exists->save();
                    if(config('themes.publish_assets', true)){
                        $this->publishAssets($theme->folder);
                    }
                }
            }
        }
    }

    public function activate($theme_folder){

        $theme = Theme::where('folder', '=', $theme_folder)->first();

        if(isset($theme->id)){
            $this->deactivateThemes();
            $theme->active = 1;
            $theme->save();
            return redirect()
                ->route("voyager.theme.index")
                ->with([
                        'message'    => "Successfully activated " . $theme->name . " theme.",
                        'alert-type' => 'success',
                    ]);
        } else {
            return redirect()
                ->route("voyager.theme.index")
                ->with([
                        'message'    => "Could not find theme " . $theme_folder . ".",
                        'alert-type' => 'error',
                    ]);
        }

    }

    public function delete(Request $request){
        $theme = Theme::find($request->id);
        if(!isset($theme)){
            return redirect()
                ->route("voyager.theme.index")
                ->with([
                        'message'    => "Could not find theme to delete",
                        'alert-type' => 'error',
                    ]);
        }

        $theme_name = $theme->name;

        // if the folder exists delete it
        if(file_exists($this->themes_folder.'/'.$theme->folder)){
            File::deleteDirectory($this->themes_folder.'/'.$theme->folder, false);
        }

        $theme->delete();

        return redirect()
                ->back()
                ->with([
                        'message'    => "Successfully deleted theme " . $theme_name,
                        'alert-type' => 'success',
                    ]);

    }

    public function options($theme_folder){

        $theme = Theme::where('folder', '=', $theme_folder)->first();

        if(isset($theme->id)){

            $options = [];

            return view('themes::options', compact('options', 'theme'));

        } else {
            return redirect()
                ->route("voyager.theme.index")
                ->with([
                        'message'    => "Could not find theme " . $theme_folder . ".",
                        'alert-type' => 'error',
                    ]);
        }
    }

    public function options_save(Request $request, $theme_folder){
        $theme = Theme::where('folder', '=', $theme_folder)->first();

        if(!isset($theme->id)){
            return redirect()
                ->route("voyager.theme.index")
                ->with([
                        'message'    => "Could not find theme " . $theme_folder . ".",
                        'alert-type' => 'error',
                    ]);
        }

        foreach($request->all() as $key => $content){

            // If we have a type checkbox and it is unchecked we need to set a value to null
            if($content == 'checkbox'){
                $field = str_replace('_type__theme_field', '', $key);
                if(!isset($request->{$field})){
                    $request->request->add([$field => null]);
                    $key = $field;
                }
            }


            if(!$this->stringEndsWith($key, '_details__theme_field') && !$this->stringEndsWith($key, '_type__theme_field') && $key != '_token'){
                
                $type = $request->{$key.'_type__theme_field'};
                $details = $request->{$key.'_details__theme_field'};
                $row = (object)['field' => $key, 'type' => $type, 'details' => $details];



                $value = $this->getContentBasedOnType($request, 'themes', $row);

                $option = ThemeOptions::where('theme_id', '=', $theme->id)->where('key', '=', $key)->first();


                // If we already have this key with the Theme ID we can update the value
                if(isset($option->id)){
                    $option->value = $value;
                    $option->save();
                } else {
                    ThemeOptions::create(['theme_id' => $theme->id, 'key' => $key, 'value' => $value]);
                }
            }
        }


        return redirect()
                ->back()
                ->with([
                        'message'    => "Successfully Saved Theme Options",
                        'alert-type' => 'success',
                    ]);


    }

    function stringEndsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
        (substr($haystack, -$length) === $needle);
    }

    private function deactivateThemes(){
        Theme::query()->update(['active' => 0]);
    }

    private function publishAssets($theme) {
        $theme_path = public_path('themes/'.$theme);

        if(!file_exists($theme_path)){
            if(!file_exists(public_path('themes'))){
                mkdir(public_path('themes'));
            }
            mkdir($theme_path);
        }

        File::copyDirectory($this->themes_folder.'/'.$theme.'/assets', public_path('themes/'.$theme));
        File::copy($this->themes_folder.'/'.$theme.'/'.$theme.'.jpg', public_path('themes/'.$theme.'/'.$theme.'.jpg'));
    }
}
