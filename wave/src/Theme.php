<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    //
    protected $table = 'themes';

    protected $fillable = ['name', 'folder', 'version'];

    public function options(): HasMany
    {
        return $this->hasMany('\Wave\ThemeOptions', 'theme_id');
    }
}
