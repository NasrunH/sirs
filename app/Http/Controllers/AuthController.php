<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function index()
    {
        // Jika user sudah login, langsung tendang ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Menangani proses autentikasi (cek username & password).
     */
    public function authenticate(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba melakukan login (Auth::attempt akan otomatis mengecek password hash)
        if (Auth::attempt($credentials)) {
            // 3. Jika berhasil, regenerate session (mencegah session fixation attack)
            $request->session()->regenerate();

            // 4. Arahkan user ke halaman dashboard (atau halaman yang mereka tuju sebelum login)
            return redirect()->intended('dashboard')->with('success', 'Selamat datang kembali, ' . Auth::user()->username . '!');
        }

        // 5. Jika gagal (username/password salah), kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'login_failed' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username'); // Mempertahankan input username agar user tidak perlu mengetik ulang
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}