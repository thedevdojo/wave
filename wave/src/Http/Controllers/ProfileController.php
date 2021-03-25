<?php

namespace Wave\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends \App\Http\Controllers\Controller
{
    public function index($username){
    	$user = config('wave.user_model')::where('username', '=', $username)->firstOrFail();
    	return view('theme::profile', compact('user'));
    }
}
