<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('home');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:1',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 1 karakter.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Check if user exists first
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar dalam sistem.'
            ])->withInput($request->except('password'));
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin')
                    ->with('success', 'Selamat datang di Admin Panel, ' . Auth::user()->name . '!');
            }
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Login berhasil! Selamat datang, ' . Auth::user()->name . '.');
        }

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.'
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home');
    }
}
