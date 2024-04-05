<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Wave\Theme;

class Themes extends Page
{
    public $themes = [];

    private $themes_folder = '';

    protected static ?string $navigationIcon = 'phosphor-paint-bucket-duotone';

    protected static ?int $navigationSort = 8;

    protected static string $view = 'filament.pages.themes';

    public function mount(){
        $this->themes_folder = config('themes.themes_folder', resource_path('views/themes'));

        $this->installThemes();
        $this->themes = Theme::all();
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
}
