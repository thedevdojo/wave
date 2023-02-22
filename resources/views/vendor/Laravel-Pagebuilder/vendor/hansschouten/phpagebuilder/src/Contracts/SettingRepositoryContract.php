<?php

namespace PHPageBuilder\Contracts;

interface SettingRepositoryContract
{
    /**
     * Replace all website settings by the given data.
     *
     * @param array $data
     * @return bool|object|null
     */
    public function updateSettings(array $data);
}
