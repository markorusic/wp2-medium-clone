<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::with('user')->paginate();
        return view('public.home', compact('posts'));
    }
}
