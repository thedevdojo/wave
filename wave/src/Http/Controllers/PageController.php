<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Wave\Page;

class PageController extends Controller
{
    public function page($slug): View
    {
        $page = Page::where('slug', '=', $slug)->firstOrFail();

        $seo = [
            'seo_title' => $page->title,
            'seo_description' => $page->meta_description,
        ];

        return view('theme::page', compact('page', 'seo'));
    }
}
