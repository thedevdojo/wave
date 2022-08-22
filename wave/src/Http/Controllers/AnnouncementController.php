<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Wave\Announcement;

class AnnouncementController extends Controller
{
    public function index(){
    	$announcements = Announcement::paginate(10);
        return view('theme::announcements.index', compact('announcements'));
    }

    public function announcement($id){
        $announcement = Announcement::find($id);
    	return view('theme::announcements.announcement', compact('announcement'));
    }

    public function read(){
    	$user = auth()->user();
    	$announcements = Announcement::all();
    	foreach($announcements as $announcement){
            if(!$user->announcements()->where('id', $announcement->id)->exists()){
    		  $user->announcements()->attach($announcement->id);
            }
    	}
    }
}
