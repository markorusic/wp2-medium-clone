<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Category};
use App\Http\Requests\{ContactRequest};

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $categories = Category::take(6)->get();
        $posts = Post::with('user')->paginate(6);
        $popular_posts = Post::popular()->with('user')->paginate(10);
        return view('public.pages.home', compact('categories', 'posts', 'popular_posts'));
    }

    public function contact(ContactRequest $request) {

    }
}
