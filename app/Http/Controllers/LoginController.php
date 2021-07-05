<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function show_login_form()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        } else {
            return view('auth-login');
        }
    }
    public function process_login(Request $request)
    {
        $user = Admin::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();
        if ($user) {
            if (password_verify($request->password, $user->password)) {
                if ($user->status == true) {
                    $request->session()->put('user', $user->username);
                    return redirect()->route('dashboard');
                } else {
                    session()->flash('error', 'Account status disabled');
                    return redirect()->back();
                }
            } else {
                session()->flash('error', 'Wrong Password');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'User not found');
            return redirect()->back();
        }
    }
    public function show_signup_form()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard');
        } else {
            return view('auth-register');
        }
    }
    public function process_signup(Request $request)
    {
        $userWhereUsername = Admin::where('username', $request->username)->first();
        $userWhereEmail = Admin::where('email', $request->email)->first();

        if ($userWhereUsername) {
            session()->flash('error', 'Username ' . $request->username . ' already exist!');
            return redirect()->route('register');
        } elseif ($userWhereEmail) {
            session()->flash('error', 'Email ' . $request->email . ' already exist!');
            return redirect()->route('register');
        } else {
            $user = Admin::create([
                'username' => trim($request->input('username')),
                'email' => strtolower($request->input('email')),
                'password' => bcrypt($request->input('password')),
            ]);

            session()->flash('success', 'Your account is created');
            return redirect()->route('login');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('user');
        return redirect()->route('login');
    }
}
