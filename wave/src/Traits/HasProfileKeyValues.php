<?php

namespace Wave\Traits;

use Wave\ProfileKeyValue;

trait HasProfileKeyValues
{
    public function profileKeyValues()
    {
        return $this->morphMany(ProfileKeyValue::class, 'keyvalue');
    }

    public function profileKeyValue($key)
    {
        return $this->profileKeyValues()->where('key', '=', $key)->first();
    }

    public function setProfileKeyValue($key, $value, $type='text')
    {
        $keyValue = $this->profileKeyValue($key);

        if (!$keyValue) {
            $keyValue = new ProfileKeyValue();
            $keyValue->key = $key;
            $keyValue->keyvalue_id = $this->id;
            $keyValue->keyvalue_type = getMorphAlias(get_class($this)) ?? get_class($this);
            $keyValue->type = $type;
        }

        $keyValue->value = $value;
        $keyValue->save();

        return $keyValue;
    }
}