<?php

if (!class_exists(WaveKeyValueConvertible::class)) {
    class WaveKeyValueConvertible
    {
        public function toObject() {

            $array = (array)$this;

            if (is_array($this)) {
                return (object)$array;
            }

            return $this;
        }
    }
}

if (!class_exists(WaveKeyValueHelper::class)) {
    class WaveKeyValueHelper extends WaveKeyValueConvertible
    {
        public $required = false;
        public $field;
        public $type;
        public $details;
        public $display_name;
        public $options = [];

        public static function create($type, $field, $details, $display_name, $required = 0, $options = []) {
            $result = new WaveKeyValueHelper();
            $result->type = $type;
            $result->field = $field;
            $result->details = $details;
            $result->display_name = $display_name;
            $result->required = $required;
            $result->options = $options;

            return $result;
        }

        public function getTranslatedAttribute($attribute) {
            return $this->display_name;
        }
    }
}

if (!class_exists(WaveKeyValueTypeHelper::class)) {
    class WaveKeyValueTypeHelper extends WaveKeyValueConvertible
    {
        protected $id = 0;
        protected $key = null;

        public function setKey($key, $content) {
            $this->key = $key;
            $this->{$key} = $content;
        }

        public static function create($key, $content) {

            $result = new WaveKeyValueTypeHelper();
            $result->setKey($key, $content);

            return $result;
        }

        public function getKey() { return $this->key; }
    }
}


if (!function_exists('wave_key_value')){

	function wave_key_value($type, $key, $content = '', $details = '', $placeholder = '', $required = 0){


        $row = WaveKeyValueHelper::create($type, $key, $details, $placeholder, $required);
        $dataTypeContent = WaveKeyValueTypeHelper::create($key, $content);
		$type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__wave_keyvalue">';

        return app('voyager')->formField($row, '', $dataTypeContent->toObject()) . $details . $type;

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
