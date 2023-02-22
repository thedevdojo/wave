<?php

namespace PHPageBuilder\Contracts;

interface SettingContract
{
    /**
     * Return the value(s) of the given setting.
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key);

    /**
     * Return whether the given setting exists and has the given value.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function has(string $key, string $value);
}
