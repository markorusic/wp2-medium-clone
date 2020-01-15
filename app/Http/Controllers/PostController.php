<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Comment, Category};

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post) {
        $post->load(['user', 'comments.user', 'likes', 'categories']);
        return view('public.pages.post-entry', compact('post'));
    }

    public function categoryPosts(Category $category) {
        $posts = $category->posts()->with('user')->paginate(10);
        return view('public.pages.category-posts', compact('category', 'posts'));
    }

    public function like(Post $post) {
        return $post->like();
    }

    public function comment(Post $post) {
        abort_unless(request()->validate([
            'content' => 'required'
        ]), 400);
        return $post->comment(request()->input('content'));
    }

    public function removeComment(Post $post, Comment $comment) {
        $user_id = auth()->id();
        if ($comment->user_id !== $user_id) {
            return response('Comment does not belong to user!', 403);
        }
        abort_unless($comment->delete(), 404);
        return response('Success', 200);
    }

    public function create() {
        return view('public.pages.create-post');
    }

    public function store(Request $request) {
        $data = collect($request->all());
        $data->put('main_photo', 'https://miro.medium.com/max/6120/0*qdqEWekIW_KHiu6P');
        return auth()->user()->posts()->create($data->toArray());
    }
}
