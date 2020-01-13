<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Category};

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $categories = Category::take(6)->get();
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $popular_posts = Post::with('user')
            ->where('created_at', '>=', now()->subDays(15)->toDateTimeString())
            ->orderBy('read_count', 'desc')
            ->take(6)
            ->get();
        return view('public.pages.home', compact('categories', 'posts', 'popular_posts'));
    }

    public function postEntry(Post $post) {
        $post->load(['user', 'comments.user', 'likes', 'categories']);
        return view('public.pages.post-entry', compact('post'));
    }
}
