<?php

namespace Wave;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $guarded = [];
    
    public function link(){
    	return url('/blog/' . $this->category->slug . '/' . $this->slug);
    }

    public function user(){
        return $this->belongsTo('\Wave\User', 'author_id');
    }

    public function image(){
    	return $this->image;
    }

    public function category(){
    	return $this->belongsTo('Wave\Category');
    }
}
