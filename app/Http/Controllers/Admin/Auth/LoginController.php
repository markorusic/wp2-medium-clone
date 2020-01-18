<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Requests\AdminLoginRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request)
    {   
        $credentials = $request->only('email','password');
        $remember = $request->filled('remember');
        if(auth('admin')->attempt($credentials, $remember)){
            return redirect()->intended(route('admin.home'));
        }
        return redirect()->back();
    }

    public function logout()
    {
        auth('admin')->logout();
        return redirect()->route('admin.login');
    }
}
