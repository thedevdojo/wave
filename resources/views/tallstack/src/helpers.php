<?php


if (!function_exists('theme_field')){

	function theme_field($type, $key, $title, $content = '', $details = '', $placeholder = '', $required = 0){

		$theme = \DevDojo\Themes\Models\Theme::where('folder', '=', ACTIVE_THEME_FOLDER)->first();

		$option_exists = $theme->options->where('key', '=', $key)->first();

		if(isset($option_exists->value)){
			$content = $option_exists->value;
		}

		$row = new class{ public function getTranslatedAttribute(){} };
		$row->required = $required;
		$row->field = $key;
		$row->type = $type;
		$row->details = $details;
		$row->display_name = $placeholder;

		$dataTypeContent = new class{ public function getKey(){} };
		$dataTypeContent->{$key} = $content;

		$label = '<label for="'. $key . '">' . $title . '<span class="how_to">You can reference this value with <code>theme(\'' . $key . '\')</code></span></label>';
		$details = '<input type="hidden" value="' . $details . '" name="' . $key . '_details__theme_field">';
		$type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__theme_field">';
		return $label . app('voyager')->formField($row, '', $dataTypeContent) . $details . $type . '<hr>';
	}

}

if (!function_exists('theme')){

	function theme($key, $default = ''){
		$theme = \DevDojo\Themes\Models\Theme::where('active', '=', 1)->first();

		if(Cookie::get('theme')){
            $theme_cookied = \DevDojo\Themes\Models\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }

		$value = $theme->options->where('key', '=', $key)->first();

		if(isset($value)) {
			return $value->value;
		}

		return $default;
	}

}

if(!function_exists('theme_folder')){
	function theme_folder($folder_file = ''){

		if(defined('THEME_FOLDER') && THEME_FOLDER){
			return 'themes/' . THEME_FOLDER . $folder_file;
		}

		$theme = \DevDojo\Themes\Models\Theme::where('active', '=', 1)->first();

		if(Cookie::get('theme')){
            $theme_cookied = \DevDojo\Themes\Models\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }

		define('THEME_FOLDER', $theme->folder);
		return 'themes/' . $theme->folder . $folder_file;
	}
}

if(!function_exists('theme_folder_url')){
	function theme_folder_url($folder_file = ''){

		if(defined('THEME_FOLDER') && THEME_FOLDER){
			return url('themes/' . THEME_FOLDER . $folder_file);
		}

		$theme = \DevDojo\Themes\Models\Theme::where('active', '=', 1)->first();

		if(Cookie::get('theme')){
            $theme_cookied = \DevDojo\Themes\Models\Theme::where('folder', '=', Cookie::get('theme'))->first();
            if(isset($theme_cookied->id)){
                $theme = $theme_cookied;
            }
        }

		define('THEME_FOLDER', $theme->folder);
		return url('themes/' . $theme->folder . $folder_file);
	}
}
