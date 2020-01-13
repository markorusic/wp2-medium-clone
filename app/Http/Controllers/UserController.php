<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{User};

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function follow(User $user) {
        return $user->follow();
    }
}
