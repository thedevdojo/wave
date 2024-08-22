<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $guarded = [];

	public function link(){
    	return url('p/' . $this->slug);
    }

    public function image(){
    	$this->image;
    }
}
