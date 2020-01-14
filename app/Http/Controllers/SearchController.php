<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\{Post, User, Category};

class SearchController extends Controller
{
    public function contentSearch(Request $request) {
        $size = $request->get('size', 5);
        $term = $request->get('term', null);

        $posts = Post::filter(['title' => $term])->paginate($size);
        $users = User::filter(['name' => $term])->paginate($size);
        $categories = Category::filter(['name' => $term])->paginate($size);

        return compact('posts', 'users', 'categories');
    }
}
