<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminAuthController extends Controller
{
    /**
     * Tampilkan halaman login admin.
     */
    public function showLogin()
    {
        // Jika sudah login, langsung ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    /**
     * Proses login admin (hanya menggunakan password).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password admin wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($request->ip()) . '|admin_login');

        // Batasi percobaan login berulang (maksimal 5 percobaan per menit)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'password' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Cari akun Admin Website utama (bisa melalui email jika diisi atau default akun Admin Utama)
        $email = $request->input('email');
        if ($email) {
            $admin = User::where('email', $email)->where('role', User::ROLE_ADMIN_WEBSITE)->first();
        } else {
            $admin = User::where('role', User::ROLE_ADMIN_WEBSITE)->first()
                ?? User::where('role', 'admin_website')->first()
                ?? User::first();
        }

        if (!$admin) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withErrors([
                'password' => 'Akun administrator belum terdaftar pada sistem atau kredensial tidak sesuai.',
            ]);
        }

        // Verifikasi password Admin Website aman menggunakan hash di database
        if (Hash::check($request->password, $admin->password)) {
            RateLimiter::clear($throttleKey);

            Auth::login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Catat kegagalan login pada rate limiter
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'password' => 'Password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
