<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
     public function create()
    {
        return view('auth.login');
    }

    
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            
            if (auth()->user()->is_active == false) {
                auth()->logout();
                return back()->withErrors([
                    'email' => 'your account is deactivated please contact the administrator'
                ]);
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Incorrect email or password'
        ]);
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}


