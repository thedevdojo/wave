<?php

if (!function_exists('wave_key_value')){

	function wave_key_value($type, $key, $content = '', $details = '', $placeholder = '', $required = 0){

		$row = (object)['required' => $required, 'field' => $key, 'type' => $type, 'details' => $details, 'display_name' => $placeholder];
		$dataTypeContent = (object)[$key => $content];
		$type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__wave_keyvalue">';
		return app('voyager')->formField($row, '', $dataTypeContent) . $type;

	}

}

if (!function_exists('profile_field')){

	function profile_field($type, $key){

		$value = auth()->user()->profile($key);
		if($value){
			return wave_key_value($type, $key, $value);
		} else {
			return wave_key_value($type, $key);
		}

	}

}

if(!function_exists('stringToColorCode')){

	function stringToColorCode($str) {
	  $code = dechex(crc32($str));
	  $code = substr($code, 0, 6);
	  return $code;
	}

}

if(!function_exists('tailwindPlanColor')){

	function tailwindPlanColor($str) {
	  $code = dechex(crc32($str));
	  $code = substr($code, 0, 6);
	  return $code;
	}

}