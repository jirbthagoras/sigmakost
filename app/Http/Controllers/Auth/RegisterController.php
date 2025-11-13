<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20|min:10',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 2 karakter.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
            'email.max' => 'Email maksimal 255 karakter.',
            'phone.min' => 'Nomor telepon minimal 10 karakter.',
            'phone.max' => 'Nomor telepon maksimal 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user', // Default role
            ]);

            Auth::login($user);

            return redirect('/dashboard')
                ->with('success', 'Akun berhasil dibuat! Selamat datang di SigmaKost, ' . $user->name . '!');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['email' => 'Terjadi kesalahan saat membuat akun. Silakan coba lagi.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }
}
