<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pasien;

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

    public function register()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.register');
    }

    /**
     * Memproses data registrasi pasien.
     */
    public function processRegister(Request $request)
    {
        $request->validate([
            'username'      => 'required|unique:users,username|max:50|alpha_dash',
            'password'      => 'required|min:4|confirmed',
            'nama_lengkap'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'        => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'pasien', 
            ]);

            // ==========================================
            // LOGIKA GENERATE NOMOR RM OTOMATIS
            // ==========================================
            $tahunBulan = date('Ym'); // Output: 202605
            
            // Cari pasien terakhir yang terdaftar di bulan ini
            $pasienTerakhir = Pasien::where('no_rekam_medis', 'LIKE', 'RM-'.$tahunBulan.'-%')
                                    ->orderBy('id_pasien', 'desc')
                                    ->first();
            
            if ($pasienTerakhir) {
                // Ambil 4 digit terakhir dari RM sebelumnya, lalu tambah 1
                $nomorTerakhir = (int) substr($pasienTerakhir->no_rekam_medis, -4);
                $nomorBaru = str_pad($nomorTerakhir + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // Jika belum ada pasien di bulan ini, mulai dari 0001
                $nomorBaru = '0001';
            }
            
            $no_rm = 'RM-' . $tahunBulan . '-' . $nomorBaru;
            // ==========================================

            Pasien::create([
                'id_user'        => $user->id_user,
                'no_rekam_medis' => $no_rm,
                'nama_lengkap'   => $request->nama_lengkap,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'alamat'         => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Nomor RM Anda: ' . $no_rm . '. Silakan login.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage())->withInput();
        }
    }
}