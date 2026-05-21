<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles (Dapat menerima banyak role, cth: 'admin', 'dokter')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['username' => 'Silakan login terlebih dahulu.']);
        }

        // 2. Ambil data role user yang sedang login
        $userRole = Auth::user()->role;

        // 3. Cek apakah role user tersebut ada di dalam daftar role yang diizinkan pada route ini
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak cocok, tolak akses (Tampilkan halaman 403 Forbidden)
        abort(403, 'Akses Ditolak. Anda tidak memiliki izin (role) untuk mengakses halaman ini.');
    }
}