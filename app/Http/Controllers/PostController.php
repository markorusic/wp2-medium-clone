<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Category, Comment};

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Post $post) {
        $post->load(['user', 'comments.user', 'likes', 'categories']);
        return view('public.pages.post-entry', compact('post'));
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
        $comment->load('user');
        $user_id = auth()->id();
        if ($comment->user->id !== $user_id) {
            return response('Comment does not belong to user!', 403);
        }
        abort_unless($comment->delete(), 404);
        return response('Success', 200);
    }
}
