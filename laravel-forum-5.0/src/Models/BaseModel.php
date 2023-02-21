<?php

namespace TeamTeaTime\Forum\Models;

use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    const DELETED_AT = 'deleted_at';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if ($this->forceDeleting) {
            $this->forceDeleting = ! config('forum.preferences.soft_deletes');
        }
    }

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function updatedSince(Model &$model): bool
    {
        return $this->updated_at > $model->updated_at;
    }

    public function hasBeenUpdated(): bool
    {
        return $this->updated_at > $this->created_at;
    }

    public function saveWithoutTouch()
    {
        $this->withoutTouch('save');
    }

    public function updateWithoutTouch(array $attributes)
    {
        $this->timestamps = false;
        $this->update($attributes);
        $this->timestamps = true;
    }

    public function deleteWithoutTouch()
    {
        $this->withoutTouch('delete');
    }

    public function forceDeleteWithoutTouch()
    {
        $this->withoutTouch('forceDelete');
    }

    public function restoreWithoutTouch()
    {
        $this->withoutTouch('restore');
    }

    protected function withoutTouch(string $method)
    {
        if (! is_callable([$this, $method])) {
            throw new \Exception("Method '{$method}' is not callable.");
        }

        $this->timestamps = false;
        $this->{$method}();
        $this->timestamps = true;
    }
}
