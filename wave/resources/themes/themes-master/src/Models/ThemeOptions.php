<?php

namespace DevDojo\Themes\Models;

use Illuminate\Database\Eloquent\Model;

class ThemeOptions extends Model
{
	protected $table = 'theme_options';
    protected $fillable = ['theme_id', 'key', 'value'];
}
