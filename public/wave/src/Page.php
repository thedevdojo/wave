<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Page extends Model
{
	public function link(){
    	return url('p/' . $this->slug);
    }

    public function image(){
    	return Voyager::image($this->image);
    }
}
