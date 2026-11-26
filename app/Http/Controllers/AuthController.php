<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'student'
                ? redirect()->route('student.dashboard')
                : redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $attempted = Auth::attempt($credentials, $request->boolean('remember'));
        } catch (\Throwable $e) {
            return back()->withErrors([
                'email' => 'Koneksi database gagal atau tabel users belum dimigrasi: ' . $e->getMessage(),
            ])->onlyInput('email');
        }

        if ($attempted) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'student') {
                return redirect()->intended(route('student.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $isStudent = Auth::check() && Auth::user()->role === 'student';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $isStudent
            ? redirect()->route('student.login')
            : redirect()->route('login');
    }
}
