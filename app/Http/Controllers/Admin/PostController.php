<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\{StorePostRequest};
use App\Http\Controllers\Controller;
use App\Models\{Post, Category};
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function indexView()
    {
        return view('admin.post.index');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $size = $request->get('size', 10);
        return Post::filter($request->all())->paginate($size);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post->load(['user', 'comments.user', 'likes.user', 'categories']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $categories = Category::all();
        $post->load('categories');
        return view('admin.post.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $post->update($request->all());
        $post->categories()->sync($request->get('categories'));
        return response('Success', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        abort_unless($post->delete(), 404);
        return response('Success', 200);
    }
}
