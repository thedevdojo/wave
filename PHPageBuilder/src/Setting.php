<?php

namespace PHPageBuilder;

use PHPageBuilder\Contracts\SettingContract;
use PHPageBuilder\Repositories\SettingRepository;

class Setting implements SettingContract
{
    protected static $settings;

    /**
     * Load all settings from database.
     */
    protected static function loadSettings()
    {
        self::$settings = [];
        $settingsRepository = new SettingRepository;
        foreach ($settingsRepository->getAll() as $setting) {
            if ($setting['is_array']) {
                self::$settings[$setting['setting']] = explode(',', $setting['value']);
            } else {
                self::$settings[$setting['setting']] = $setting['value'];
            }
        }
    }

    /**
     * Return the value(s) of the given setting.
     *
     * @param string $key
     * @return mixed|array|null
     */
    public static function get(string $key)
    {
        if (is_null(self::$settings)) {
            self::loadSettings();
        }

        if (isset(self::$settings[$key])) {
            return self::$settings[$key];
        }
        return null;
    }

    /**
     * Return whether the given setting exists and has the given value.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function has(string $key, string $value)
    {
        if (is_null(self::$settings)) {
            self::loadSettings();
        }

        return isset(self::$settings[$key]) && (
            (is_array(self::$settings[$key]) && in_array($value, self::$settings[$key])) ||
            (! is_array(self::$settings[$key]) && self::$settings[$key] === $value)
        );
    }
}
