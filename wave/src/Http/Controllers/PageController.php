<?php

namespace Wave\Http\Controllers;

use Wave\Page;
use Illuminate\Http\Request;

class PageController extends \App\Http\Controllers\Controller
{
    public function page($slug){
    	$page = Page::where('slug', '=', $slug)->firstOrFail();

    	$seo = [
            'seo_title' => $page->title,
            'seo_description' => $page->meta_description,
        ];

    	return view('theme::page', compact('page', 'seo'));
    }
}
