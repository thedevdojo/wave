<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
	public function users(){
		return $this->belongsToMany('Wave\User');
	}
}
