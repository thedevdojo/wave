<?php

use Illuminate\Database\Eloquent\Relations\Relation;

if (!class_exists(KeyValueConvertible::class)) {
    class KeyValueConvertible
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

if (!class_exists(KeyValueHelper::class)) {
    class KeyValueHelper extends KeyValueConvertible
    {
        public $required = false;
        public $field;
        public $type;
        public $details;
        public $display_name;
        public $options = [];

        public static function create($type, $field, $details, $display_name, $required = 0, $options = []) {
            $result = new KeyValueHelper();
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

if (!class_exists(KeyValueTypeHelper::class)) {
    class KeyValueTypeHelper extends KeyValueConvertible
    {
        protected $id = 0;
        protected $key = null;

        public function setKey($key, $content) {
            $this->key = $key;
            $this->{$key} = $content;
        }

        public static function create($key, $content) {

            $result = new KeyValueTypeHelper();
            $result->setKey($key, $content);

            return $result;
        }

        public function getKey() { return $this->key; }
    }
}


if (!function_exists('key_value')){

	function key_value($type, $key, $content = '', $details = '', $placeholder = '', $required = 0){


        $row = KeyValueHelper::create($type, $key, $details, $placeholder, $required);
        $dataTypeContent = KeyValueTypeHelper::create($key, $content);
		$type = '<input type="hidden" value="' . $type . '" name="' . $key . '_type__keyvalue">';

        return 'use filament form builder here';
        //return app('voyager')->formField($row, '', $dataTypeContent->toObject()) . $details . $type;

	}

}

if (!function_exists('profile_field')){

	function profile_field($type, $key){

		$value = auth()->user()->profile($key);
		if($value){
			return key_value($type, $key, $value);
		} else {
			return key_value($type, $key);
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

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        $value = ($default == null) ? '' : $default;

        return $value;
    }
}

if (!function_exists('blade')) {
    function blade($string){
        return \Illuminate\Support\Facades\Blade::render($string);
    }
}

if (!function_exists('getMorphAlias')) {
    /**
     * Get the morph alias for a given class.
     *
     * @param string $class
     * @return string|null
     */
    function getMorphAlias($class)
    {
        $morphMap = Relation::morphMap();
        $alias = array_search($class, $morphMap);
        return $alias ?: null;
    }
}