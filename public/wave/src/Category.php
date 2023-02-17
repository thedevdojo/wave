<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function posts(){
    	return $this->hasMany('Wave\Post');
    }
}
