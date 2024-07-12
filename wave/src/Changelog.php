<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
	protected $fillable = ['title', 'description', 'body'];
	public function users(){
		return $this->belongsToMany('Wave\User');
	}
}
