<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->role !== 'mahasiswa') {
                return redirect('/admin');
            }

            return redirect()->route('loans.create');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'mahasiswa',
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('loans.create'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            if (Auth::user()->role !== 'mahasiswa') {
                return redirect('/admin');
            }

            return redirect()->route('loans.create');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:50|unique:users,nim',
            'study_program' => 'required|string|max:255',
            'phone_number' => 'required|string|max:30',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar di sistem.',
            'study_program.required' => 'Program studi wajib diisi.',
            'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'mahasiswa',
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan masuk menggunakan email dan password Anda.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
