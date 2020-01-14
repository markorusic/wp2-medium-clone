<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{Post, Comment};

use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index(Request $request) {
        return Post::filter($request->all())->paginate(
            $request->get('size', 10)
        );
    }

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
        $user_id = auth()->id();
        if ($comment->user_id !== $user_id) {
            return response('Comment does not belong to user!', 403);
        }
        abort_unless($comment->delete(), 404);
        return response('Success', 200);
    }
}
