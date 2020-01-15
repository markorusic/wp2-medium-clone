<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Comment, Category};

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post) {
        $post->load(['user', 'comments.user', 'likes', 'categories']);
        return view('public.pages.post', compact('post'));
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
        abort_if($comment->user_id !== auth()->id(), 403);
        abort_unless($comment->delete(), 404);
        return response('Success', 200);
    }

    public function create() {
        $categories = Category::all();
        return view('public.pages.post-create', compact('categories'));
    }

    public function store(Request $request) {
        $data = collect($request->all());
        $categories = $data->get('categories', []);
        $post = auth()->user()->posts()->create($data->toArray());
        $post->categories()->sync($categories);
        return $post;
    }

    public function edit(Post $post) {
        abort_if($post->user_id !== auth()->id(), 403);
        $post->load('categories');
        $categories = Category::all();
        return view('public.pages.post-edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post) {
        abort_if($post->user_id !== auth()->id(), 403);
        $data = collect($request->all());
        $categories = $data->get('categories', []);
        $post->update($data->except('categories')->toArray());
        $post->categories()->sync($categories);
        return $post;
    }

    public function destroy(Post $post) {
        abort_if($post->user_id !== auth()->id(), 403);
        abort_unless($post->delete(), 404);
        return response('Success', 200);
    }
}
