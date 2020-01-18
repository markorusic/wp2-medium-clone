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

    /**
     * Show the login form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.auth.login');
    }

    /**
     * Login the admin.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(AdminLoginRequest $request)
    {   
        $credentials = $request->only('email','password');
        $remember = $request->filled('remember');
        if(auth('admin')->attempt($credentials, $remember)){
            return redirect()
                ->intended(route('admin.home'))
                ->with('status','You are Logged in as Admin!');
        }
        return redirect()
            ->back()
            ->withInput()
            ->with('error','Login failed, please try again!');
    }

    /**
     * Logout the admin.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth('admin')->logout();
        return redirect()->route('admin.login');
    }
}
