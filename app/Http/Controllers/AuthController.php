<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ១. បង្ហាញផ្ទាំង Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // ២. ដំណើរការ Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard'); // ចូលបានហើយ ទៅ Dashboard
        }

        return back()->with('error', 'អ៊ីមែល ឬពាក្យសម្ងាត់មិនត្រឹមត្រូវ!');
    }

    // ៣. ចាកចេញ (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}