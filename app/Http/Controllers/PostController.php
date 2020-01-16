<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Comment, Category};

use Illuminate\Http\Request;
use \Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    public function show(Post $post) {
        $post->load(['user', 'categories']);
        $post->loadCount('likes');
        return view('public.pages.post', compact('post'));
    }

    public function comments(Post $post, Request $request) {
        $size = $request->get('size', 5);
        return $post->comments()->with('user')->paginate($size);
    }

    public function likes(Post $post, Request $request) {
        $size = $request->get('size', 5);
        $likes = $post->likes()->with('user')->paginate($size);
        $users = $likes->getCollection()->map(function ($like) {
            return $like->user;
        });
        return new LengthAwarePaginator(
            $users,
            $likes->total(),
            $likes->perPage(),
            $likes->currentPage(), [
                'path' => \Request::url(),
                'query' => [
                    'page' => $likes->currentPage()
                ]
            ]
        );
    }

    public function categoryPosts(Category $category) {
        $posts = $category->posts()->with('user')->paginate(10);
        return view('public.pages.category-posts', compact('category', 'posts'));
    }

    public function popularPosts() {
        $posts = Post::with('user')->popular()->paginate();
        return view('public.pages.popular-posts', compact('posts'));
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
