<?php

use PHPageBuilder\Extensions;

if (! function_exists('phpb_e')) {
    /**
     * Encode HTML special characters in a string.
     *
     * @param string $value
     * @param bool $doubleEncode
     * @return string
     */
    function phpb_e($value, $doubleEncode = true)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}

if (! function_exists('phpb_encode_or_null')) {
    /**
     * Encode HTML special characters in a string, but preserve a null value if the passed input equals null.
     *
     * @param string $value
     * @param bool $doubleEncode
     * @return string
     */
    function phpb_encode_or_null($value, $doubleEncode = true)
    {
        return is_null($value) ? null : phpb_e($value, $doubleEncode);
    }
}

if (! function_exists('phpb_asset')) {
    /**
     * Return the public path of a PHPageBuilder asset.
     *
     * @param string $path
     * @return string
     */
    function phpb_asset($path)
    {
        return phpb_full_url(phpb_config('general.assets_url') . '/' . $path);
    }
}

if (! function_exists('phpb_theme_asset')) {
    /**
     * Return the public path of an asset of the current theme.
     *
     * @param string $path
     * @return string
     */
    function phpb_theme_asset($path)
    {
        $themeFolder = phpb_config('theme.folder_url') . '/' . phpb_config('theme.active_theme');
        return phpb_full_url($themeFolder . '/' . $path);
    }
}

if (! function_exists('phpb_flash')) {
    /**
     * Return the flash data with the given key (as dot-separated multidimensional array selector) or false if not set.
     *
     * @param $key
     * @param bool $encode
     * @return bool|mixed
     */
    function phpb_flash($key, $encode = true)
    {
        global $phpb_flash;

        // if no dot notation is used, return first dimension value or empty string
        if (strpos($key, '.') === false) {
            if (! isset($phpb_flash[$key])) {
                return false;
            }
            return $encode ? phpb_e($phpb_flash[$key]) : $phpb_flash[$key];
        }

        // if dot notation is used, traverse config string
        $segments = explode('.', $key);
        $subArray = $phpb_flash;
        foreach ($segments as $segment) {
            if (isset($subArray[$segment])) {
                $subArray = &$subArray[$segment];
            } else {
                return false;
            }
        }

        // if the remaining sub array is a string, return this piece of flash data
        if (is_string($subArray)) {
            if ($encode) {
                return phpb_e($subArray);
            }
            return $subArray;
        }
        return false;
    }
}

if (! function_exists('phpb_config')) {
    /**
     * Return the configuration with the given key (as dot-separated multidimensional array selector).
     *
     * @param string $key
     * @return mixed
     */
    function phpb_config($key)
    {
        global $phpb_config;

        // if no dot notation is used, return first dimension value or empty string
        if (strpos($key, '.') === false) {
            return $phpb_config[$key] ?? '';
        }

        // if dot notation is used, traverse config string
        $segments = explode('.', $key);
        $subArray = $phpb_config;
        foreach ($segments as $segment) {
            if (isset($subArray[$segment])) {
                $subArray = &$subArray[$segment];
            } else {
                return '';
            }
        }

        return $subArray;
    }
}

if (! function_exists('phpb_trans')) {
    /**
     * Return the translation of the given key (as dot-separated multidimensional array selector).
     *
     * @param $key
     * @param array $parameters
     * @return string|array
     */
    function phpb_trans($key, $parameters = [])
    {
        global $phpb_translations;

        // if no dot notation is used, return first dimension value or empty string
        if (strpos($key, '.') === false) {
            return phpb_replace_placeholders($phpb_translations[$key] ?? '', $parameters);
        }

        // if dot notation is used, traverse translations string
        $segments = explode('.', $key);
        $subArray = $phpb_translations;
        foreach ($segments as $segment) {
            if (isset($subArray[$segment])) {
                $subArray = &$subArray[$segment];
            } else {
                return '';
            }
        }

        // if the remaining sub array is a non-empty string/array, return this translation or translations structure
        if (! empty($subArray)) {
            if (is_string($subArray)) {
                return phpb_replace_placeholders($subArray, $parameters);
            }
            return $subArray;
        }
        return '';
    }
}

if (! function_exists('phpb_replace_placeholders')) {
    /**
     * Replace in the given string the given parameter placeholders with corresponding values.
     *
     * @param $string
     * @param array $parameters
     * @return string
     */
    function phpb_replace_placeholders($string, $parameters = [])
    {
        foreach ($parameters as $placeholder => $value) {
            $string = str_replace(':' . $placeholder, $value, $string);
        }
        return $string;
    }
}


if (! function_exists('phpb_full_url')) {
    /**
     * Give the full URL of a given URL which is relative to the base URL.
     *
     * @param string $urlRelativeToBaseUrl
     * @return string
     */
    function phpb_full_url($urlRelativeToBaseUrl)
    {
        // if the URL is already a full URL, do not alter the URL
        if (strpos($urlRelativeToBaseUrl, 'http://') === 0 || strpos($urlRelativeToBaseUrl, 'https://') === 0) {
            return $urlRelativeToBaseUrl;
        }

        $baseUrl = phpb_config('general.base_url');
        return rtrim($baseUrl, '/') . $urlRelativeToBaseUrl;
    }
}

if (! function_exists('phpb_url')) {
    /**
     * Give the full URL of a given public path.
     *
     * @param string $module
     * @param array $parameters
     * @param bool $fullUrl
     * @return string
     */
    function phpb_url($module, array $parameters = [], $fullUrl = true)
    {
        $url = $fullUrl ? phpb_full_url('') : '';
        $url .= phpb_config($module . '.url');

        if (! empty($parameters)) {
            $url .= '?';
            $pairs = [];
            foreach ($parameters as $key => $value) {
                $pairs[] = phpb_e($key) . '=' . phpb_e($value);
            }
            $url .= implode('&', $pairs);
        }

        return $url;
    }
}

if (! function_exists('phpb_current_full_url')) {
    /**
     * Give the current full URL.
     *
     * @return string|null
     */
    function phpb_current_full_url()
    {
        // return null when running form CLI
        if (! isset($_SERVER['SERVER_NAME']) || ! isset($_SERVER['REQUEST_URI'])) {
            return null;
        }

        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
        $port = '';
        if (isset($_SERVER['SERVER_PORT']) && ! in_array($_SERVER['SERVER_PORT'], [80, 443])) {
            $port = ":" . $_SERVER['SERVER_PORT'];
        }

        $currentFullUrl = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . urldecode($_SERVER['REQUEST_URI']);
        $currentFullUrl = rtrim($currentFullUrl, '/' . DIRECTORY_SEPARATOR);
        return $currentFullUrl;
    }
}

if (! function_exists('phpb_current_relative_url')) {
    /**
     * Give the current URL relative to the base directory (the website's index.php entry point).
     * This omits any parent directories from the URL in which the project is installed.
     *
     * @return string
     */
    function phpb_current_relative_url()
    {
        $baseUrl = phpb_config('general.base_url');
        $baseUrl = rtrim($baseUrl, '/'. DIRECTORY_SEPARATOR);

        $currentFullUrl = phpb_current_full_url();
        $relativeUrl = substr($currentFullUrl, strlen($baseUrl));
        $relativeUrl = ltrim($relativeUrl, '/'. DIRECTORY_SEPARATOR);
        return '/' . $relativeUrl;
    }
}

if (! function_exists('phpb_current_language')) {
    /**
     * Give the current language based on the current URL.
     *
     * @return string
     */
    function phpb_current_language()
    {
        $urlComponents = explode('/', phpb_current_relative_url());
        // remove empty values and reset array key numbering
        $urlComponents = array_values(array_filter($urlComponents));
        if (! empty($urlComponents)) {
            foreach (phpb_active_languages() as $languageCode => $languageTranslation) {
                if ($urlComponents[0] === $languageCode) {
                    return $languageCode;
                }
            }
        }
        $languageCode = phpb_config('general.language');
        if (in_array($languageCode, array_keys(phpb_active_languages()))) {
            return $languageCode;
        }
        if (in_array('en', array_keys(phpb_active_languages()))) {
            return 'en';
        }
        return array_keys(phpb_active_languages())[0];
    }
}

if (! function_exists('phpb_in_module')) {
    /**
     * Return whether we are currently accessing the given module.
     *
     * @param string $module
     * @return bool
     */
    function phpb_in_module($module)
    {
        $url = phpb_url($module, [], false);
        $currentUrl = explode('?', phpb_current_relative_url(), 2)[0];
        return $currentUrl === $url;
    }
}

if (! function_exists('phpb_on_url')) {
    /**
     * Return whether we are currently on the given URL.
     *
     * @param string $module
     * @param array $parameters
     * @return bool
     */
    function phpb_on_url($module, array $parameters = [])
    {
        $url = phpb_url($module, $parameters, false);
        return phpb_current_relative_url() === $url;
    }
}

if (! function_exists('phpb_set_in_editmode')) {
    /**
     * Set whether the current page is being load in edit mode (i.e. inside the page builder).
     *
     * @param bool $inEditMode
     */
    function phpb_set_in_editmode($inEditMode = true)
    {
        global $phpb_in_editmode;

        $phpb_in_editmode = $inEditMode;
    }
}

if (! function_exists('phpb_in_editmode')) {
    /**
     * Return whether the current page is load in edit mode (i.e. inside the page builder).
     *
     * @return bool
     */
    function phpb_in_editmode()
    {
        global $phpb_in_editmode;

        return $phpb_in_editmode ?? false;
    }
}

if (! function_exists('phpb_redirect')) {
    /**
     * Redirect to the given URL with optional session flash data.
     *
     * @param string $url
     * @param array $flashData
     * @param $statusCode
     */
    function phpb_redirect($url, $flashData = [], $statusCode = 302)
    {
        if (! empty($flashData)) {
            $_SESSION["phpb_flash"] = $flashData;
        }

        header('Location: ' . $url, true, $statusCode);
        exit();
    }
}

if (! function_exists('phpb_route_parameters')) {
    /**
     * Return the named route parameters resolved from the current URL.
     *
     * @return array|null
     */
    function phpb_route_parameters()
    {
        global $phpb_route_parameters;

        return $phpb_route_parameters ?? [];
    }
}

if (! function_exists('phpb_route_parameter')) {
    /**
     * Return the value of the given named route parameter resolved from the current URL.
     *
     * @param string $parameter
     * @return string|null
     */
    function phpb_route_parameter($parameter)
    {
        global $phpb_route_parameters;

        return $phpb_route_parameters[$parameter] ?? null;
    }
}

if (! function_exists('phpb_field_value')) {
    /**
     * Return the posted value or the attribute value of the given instance, or null if no value was found.
     *
     * @param $attribute
     * @param object $instance
     * @return string|null
     */
    function phpb_field_value($attribute, $instance = null)
    {
        if (isset($_POST[$attribute])) {
            return phpb_encode_or_null($_POST[$attribute]);
        }
        if (isset($instance)) {
            if (method_exists($instance, 'get')) {
                return phpb_encode_or_null($instance->get($attribute));
            } else {
                return phpb_encode_or_null($instance->$attribute);
            }
        }
        return null;
    }
}

if (! function_exists('phpb_active_languages')) {
    /**
     * Return the list of all active languages.
     *
     * @return array
     */
    function phpb_active_languages()
    {
        $configLanguageCode = phpb_config('general.language');
        $languages = phpb_instance('setting')::get('languages') ?? [$configLanguageCode];

        // if the array has numeric indices (which is the default), create a languageCode => languageTranslation structure
        if (array_values($languages) === $languages) {
            $newLanguagesStructure = [];
            foreach ($languages as $languageCode) {
                $newLanguagesStructure[$languageCode] = phpb_trans('languages')[$languageCode] ?? [];
            }
            $languages = $newLanguagesStructure;
        }

        if (! isset($languages[$configLanguageCode])) {
            return $languages;
        }

        // sort languages, starting by the configured language
        $languagesSorted[$configLanguageCode] = $languages[$configLanguageCode];
        foreach ($languages as $languageCode => $languageTranslation) {
            if ($languageCode !== $configLanguageCode) {
                $languagesSorted[$languageCode] = $languageTranslation;
            }
        }
        return $languagesSorted;
    }
}

if (! function_exists('phpb_instance')) {
    /**
     * Return an instance of the given class as defined in config, or with the given namespace (which is potentially overridden and mapped to an alternative namespace).
     *
     * @param string $name          the name of the config main section in which the class path is defined
     * @param array $params
     * @return object|null
     */
    function phpb_instance(string $name, $params = [])
    {
        if (phpb_config($name . '.class')) {
            $className = phpb_config($name . '.class');
            return new $className(...$params);
        }
        if (class_exists($name)) {
            if (phpb_config('class_replacements.' . $name)) {
                $replacement = phpb_config('class_replacements.' . $name);
                return new $replacement(...$params);
            }
            return new $name(...$params);
        }
        return null;
    }
}

if (! function_exists('phpb_static')) {
    /**
     * Return a static reference of the given class as defined in config, or with the given namespace (which is potentially overridden and mapped to an alternative namespace).
     *
     * @param string $name          the name of the config main section in which the class path is defined
     * @return object|string|null
     */
    function phpb_static(string $name)
    {
        if (phpb_config($name . '.class')) {
            return phpb_config($name . '.class');
        }
        if (class_exists($name)) {
            if (phpb_config('class_replacements.' . $name)) {
                return phpb_config('class_replacements.' . $name);
            }
            return $name;
        }
        return null;
    }
}

if (! function_exists('phpb_slug')) {
    /**
     * Create a slug (safe URL or path) of the given string.
     *
     * @param string $text
     * @param false $allowSlashes
     * @return string
     */
    function phpb_slug(string $text, $allowSlashes = false)
    {
        if ($allowSlashes) {
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-\/]+/', '-', $text)));
        } else {
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $text)));
        }
    }
}

if (! function_exists('phpb_autoload')) {
    /**
     * Autoload classes from the PHPageBuilder package.
     *
     * @param  string $className
     */
    function phpb_autoload($className)
    {
        // PSR-0 autoloader
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strripos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        // remove leading PHPageBuilder/ from the class path
        $fileName = str_replace('PHPageBuilder' . DIRECTORY_SEPARATOR, '', $fileName);

        // include class files starting in the src directory
        require __DIR__ . '/../' . $fileName;
    }
}

if (! function_exists('phpb_registered_assets')) {
    /**
     * Render all manually registered assets.
     *
     * @param $location
     * @return void
     */
    function phpb_registered_assets($location = 'header') {
        $assets = ($location === 'header') ? Extensions::getHeaderAssets() : Extensions::getFooterAssets();

        foreach ($assets as $asset) {
            $attributes = '';
            foreach ($asset['attributes'] as $key => $value) {
                $attributes .= ' ' . $key . '="' . $value . '"';
            }

            if ($asset['type'] == 'style') {
                echo '<link rel="stylesheet" href="' . $asset['src'] . '" ' . $attributes . '/>';
            } else {
                echo '<script type="text/javascript" src="' . $asset['src'] . '" ' . $attributes . '></script>';
            }
        }
    }
}
