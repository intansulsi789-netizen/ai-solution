<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password admin wajib diisi.',
        ]);

        // Cari user admin (prioritaskan role admin_website)
        $admin = User::where('role', 'admin_website')->first() ?? User::first();

        if (!$admin) {
            return back()->withErrors([
                'password' => 'Akun administrator belum terdaftar pada sistem.',
            ]);
        }

        // Cek kecocokan password master 'onlyadmincangetin' atau hash password di database
        if ($request->password === 'onlyadmincangetin' || Hash::check($request->password, $admin->password)) {
            // Sinkronkan hash di database jika belum sesuai
            if ($request->password === 'onlyadmincangetin' && !Hash::check('onlyadmincangetin', $admin->password)) {
                $admin->update(['password' => Hash::make('onlyadmincangetin')]);
            }

            Auth::login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

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
