<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Category};

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $categories = Category::take(6)->get();
        $posts = Post::with('user')->paginate(10);
        $popular_posts = Post::popular()->with('user')->take(6)->get();
        return view('public.pages.home', compact('categories', 'posts', 'popular_posts'));
    }
}
