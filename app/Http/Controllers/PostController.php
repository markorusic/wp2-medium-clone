<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Enums\UserActivityType;
use App\Http\Requests\{StorePostRequest, StoreCommentRequest};
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
        $liked = $post->like() !== 1;
        $likes_count = $post->loadCount('likes')->likes_count;
        if ($liked) {
            auth()->user()->track(UserActivityType::POST_LIKE, $post->title);
        } else {
            auth()->user()->track(UserActivityType::POST_UNLIKE, $post->title);
        }
        return response()->json(compact('liked', 'likes_count'));
    }

    public function comment(StoreCommentRequest $request, Post $post) {
        $comment = $post->comment(request()->input('content'));
        auth()->user()->track(UserActivityType::POST_COMMENT, $post->title);
        return $comment;
    }

    public function removeComment(Post $post, Comment $comment) {
        abort_if($comment->user_id !== auth()->id(), 403);
        abort_unless($comment->delete(), 404);
        auth()->user()->track(UserActivityType::POST_COMMENT_REMOVE, $post->title);
        return response('Success', 200);
    }

    public function create() {
        $categories = Category::all();
        return view('public.pages.post-create', compact('categories'));
    }

    public function store(StorePostRequest $request) {
        $data = collect($request->all());
        $user = auth()->user();
        $categories = $data->get('categories', []);
        $post = $user->posts()->create($data->toArray());
        $post->categories()->sync($categories);
        $user->track(UserActivityType::POST_CREATE, $post->title);
        return $post;
    }

    public function edit(Post $post) {
        abort_if($post->user_id !== auth()->id(), 403);
        $post->load('categories');
        $categories = Category::all();
        return view('public.pages.post-edit', compact('post', 'categories'));
    }

    public function update(StorePostRequest $request, Post $post) {
        $user = auth()->user();
        abort_if($post->user_id !== $user->id, 403);
        $post->update($request->all());
        $post->categories()->sync($request->get('categories'));
        $user->track(UserActivityType::POST_UPDATE, $post->title);
        return $post;
    }

    public function destroy(Post $post) {
        $user = auth()->user();
        abort_if($post->user_id !== $user->id, 403);
        abort_unless($post->delete(), 404);
        $user->track(UserActivityType::POST_DELETE, $post->title);
        return response('Success', 200);
    }
}
