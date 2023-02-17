<?php

namespace Wave\Http\Controllers;

use App\Http\Controllers\Controller;
use Wave\Category;
use Wave\Post;

class BlogController extends Controller
{
    public function index(){

        $posts = Post::orderBy('created_at', 'DESC')->paginate(6);
        $categories = Category::all();

    	$seo = [
    		'seo_title' => 'Blog',
            'seo_description' => 'Our Blog',
       	];

    	return view('theme::blog.index', compact('posts', 'categories', 'seo'));
    }

    public function category($slug){

        $category = Category::where('slug', '=', $slug)->firstOrFail();
        $posts = $category->posts()->orderBy('created_at', 'DESC')->paginate(6);
        $categories = Category::all();

        $seo = [
            'seo_title' => $category->name . '- Blog',
            'seo_description' => $category->name . '- Blog',
        ];

        return view('theme::blog.index', compact('posts', 'category', 'categories', 'seo'));
    }

    public function post($category, $slug){

    	$post = Post::where('slug', '=', $slug)->firstOrFail();
        $category = Category::where('slug', '=', $category)->firstOrFail();
        
        $seo = [
            'seo_title' => $post->title,
            'seo_description' => $post->seo_description,
        ];

    	return view('theme::blog.post', compact('post', 'seo'));
    }
}
