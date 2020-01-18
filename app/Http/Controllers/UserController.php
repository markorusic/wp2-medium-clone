<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Enums\UserActivityType;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user) {
        $user->loadCount('followers')->loadCount('following');
        $posts = $user->posts()->paginate();
        return view('public.pages.user-profile', compact('user', 'posts'));
    }

    public function edit() {
        $user = auth()->user();
        return view('public.pages.user-profile-edit', compact('user'));
    }

    public function activity() {
        $user = auth()->user();
        $activities = $user->activities()->paginate(12);
        return view('public.pages.user-activity', compact('user', 'activities'));
    }

    public function update(UpdateUserRequest $request) {
        $user = auth()->user();
        $user->update($request->all());
        $user->track(UserActivityType::PROFILE_UPDATED);
        return $user;
    }

    public function followers(User $user, Request $request) {
        $size = $request->get('size', 5);
        return $user->followers()->paginate($size);
    }

    public function following(User $user, Request $request) {
        $size = $request->get('size', 5);
        return $user->following()->paginate($size);
    }

    public function follow(User $user) {
        $followed = $user->follow() !== 1;
        $followers_count = $user->loadCount('followers')->followers_count;
        if ($followed) {
            auth()->user()->track(UserActivityType::USER_FOLLOW, $user->name);
        } else {
            auth()->user()->track(UserActivityType::USER_UNFOLLOW, $user->name);
        }
        return response()->json(compact('followed', 'followers_count'));
    }
}
