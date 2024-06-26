<?php

namespace Wave\Traits;

use Wave\KeyValue;

trait HasKeyValues
{
    public function keyValues()
    {
        return $this->morphMany(KeyValue::class, 'keyvalue');
    }

    public function keyValue($key)
    {
        return $this->keyValues()->where('key', '=', $key)->first();
    }

    public function setKeyValue($key, $value, $type='text')
    {
        $keyValue = $this->keyValue($key);

        if (!$keyValue) {
            $keyValue = new KeyValue();
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