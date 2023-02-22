<?php

namespace PHPageBuilder\Repositories;

use PHPageBuilder\Contracts\SettingRepositoryContract;

class SettingRepository extends BaseRepository implements SettingRepositoryContract
{
    /**
     * The pages database table.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * Replace all website settings by the given data.
     *
     * @param array $data
     * @return bool|object|null
     */
    public function updateSettings(array $data)
    {
        $this->destroyAll();


        foreach ($data as $key => $value) {
            $isArray = is_array($value);
            if ($isArray) {
                $value = implode(',', $value);
            }

            $this->create([
                'setting' => $key,
                'value' => $value,
                'is_array' => $isArray,
            ]);
        }

        return true;
    }
}
