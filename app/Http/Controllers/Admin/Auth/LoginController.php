<?php

namespace App\Http\Controllers\Admin\Auth;

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

    public function login(Request $request)
    {   
        $this->validate($request, [
            'email'    => 'required|email|exists:admins|min:5|max:191',
            'password' => 'required|string|min:4|max:255'
        ]);

        $credentials = $request->only('email','password');
        $remember = $request->filled('remember');

        if(auth('admin')->attempt($credentials, $remember)){
            return redirect()->intended(route('admin.home'));
        }

        return redirect()
            ->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([ 'email' => 'Auth failed' ]);
    }

    public function logout()
    {
        auth('admin')->logout();
        return redirect()->route('admin.login');
    }
}
