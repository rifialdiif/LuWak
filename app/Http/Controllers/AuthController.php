<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'nip_nim' => ['required'],
            'password' => ['required'],
        ]);

        // Asumsi field password di DB: password_hash
        if (Auth::attempt(['nip_nim' => $credentials['nip_nim'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            session()->flash('welcome', 'Welcome to Delusi, ' . Auth::user()->nama . '!');
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'nip_nim' => 'NIP/NIM atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
