<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Dokter;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Data Login Utama
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:4',
            'role'     => 'required|in:admin,dokter,pasien',
        ]);

        DB::beginTransaction();
        try {
            // 2. Simpan Akun User
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
            ]);

            // 3. Simpan Profil Berdasarkan Role yang Dipilih
            if ($request->role === 'admin') {
                $request->validate(['nama_admin' => 'required|string|max:100']);
                Admin::create([
                    'id_user' => $user->id_user,
                    'nama_admin' => $request->nama_admin
                ]);
            } 
            elseif ($request->role === 'dokter') {
                $request->validate([
                    'nama_dokter' => 'required|string|max:100',
                    'spesialisasi' => 'required|string|max:50',
                ]);
                Dokter::create([
                    'id_user' => $user->id_user,
                    'nama_dokter' => $request->nama_dokter,
                    'spesialisasi' => $request->spesialisasi,
                    'no_telp' => $request->no_telp,
                ]);
            } 
            elseif ($request->role === 'pasien') {
                $request->validate([
                    // no_rekam_medis tidak perlu divalidasi lagi
                    'nama_lengkap' => 'required|string|max:100',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|in:L,P',
                ]);

                // === GENERATE RM ===
                $tahunBulan = date('Ym');
                $pasienTerakhir = \App\Models\Pasien::where('no_rekam_medis', 'LIKE', 'RM-'.$tahunBulan.'-%')->orderBy('id_pasien', 'desc')->first();
                $nomorBaru = $pasienTerakhir ? str_pad((int)substr($pasienTerakhir->no_rekam_medis, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';
                $no_rm = 'RM-' . $tahunBulan . '-' . $nomorBaru;
                // ===================

                \App\Models\Pasien::create([
                    'id_user' => $user->id_user,
                    'no_rekam_medis' => $no_rm, // Gunakan hasil generate
                    'nama_lengkap' => $request->nama_lengkap,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'alamat' => $request->alamat,
                ]);
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Akun Pengguna & Profil berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.$id.',id_user',
        ]);

        // Demi keamanan struktur relasional, Role tidak boleh diubah melalui form edit ini.
        // Jika ingin ubah role, user harus dihapus dan dibuat ulang.
        
        $userData = ['username' => $request->username];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('users.index')->with('success', 'Kredensial Akun Pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        $user = User::findOrFail($id);
        // Karena di migration kita menggunakan onDelete('cascade') atau nullable, menghapus user akan aman.
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'Akun Pengguna berhasil dihapus!');
    }
}