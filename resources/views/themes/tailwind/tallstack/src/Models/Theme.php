<?php

namespace DevDojo\Themes\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    //
    protected $table = 'themes';
    protected $fillable = ['name', 'folder', 'version'];

    public function options(){
    	return $this->hasMany('\DevDojo\Themes\Models\ThemeOptions', 'theme_id');
    }
}
