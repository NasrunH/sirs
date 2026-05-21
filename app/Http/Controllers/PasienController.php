<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{
    public function index()
    {
        // Load data pasien beserta data user-nya (akun loginnya)
        $pasien = Pasien::with('user')->orderBy('created_at', 'desc')->get();
        return view('pasien.index', compact('pasien'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username',
            'password'       => 'required|string|min:4',
            // no_rekam_medis dihapus dari validasi karena di-generate sistem
            'nama_lengkap'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'alamat'         => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'pasien',
            ]);

            // === GENERATE RM ===
            $tahunBulan = date('Ym');
            $pasienTerakhir = Pasien::where('no_rekam_medis', 'LIKE', 'RM-'.$tahunBulan.'-%')->orderBy('id_pasien', 'desc')->first();
            $nomorBaru = $pasienTerakhir ? str_pad((int)substr($pasienTerakhir->no_rekam_medis, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';
            $no_rm = 'RM-' . $tahunBulan . '-' . $nomorBaru;
            // ===================

            Pasien::create([
                'id_user'        => $user->id_user,
                'no_rekam_medis' => $no_rm, // Menggunakan nomor yang digenerate
                'nama_lengkap'   => $request->nama_lengkap,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'alamat'         => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('pasien.index')->with('success', 'Pasien berhasil dibuat dengan No. RM: ' . $no_rm);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'username'       => 'required|string|max:50|unique:users,username,'.$pasien->id_user.',id_user',
            'no_rekam_medis' => 'required|unique:pasien,no_rekam_medis,'.$id.',id_pasien',
            'nama_lengkap'   => 'required|string|max:100',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Akun User
            $userData = ['username' => $request->username];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            User::where('id_user', $pasien->id_user)->update($userData);

            // 2. Update Profil Pasien
            $pasien->update([
                'no_rekam_medis' => $request->no_rekam_medis,
                'nama_lengkap'   => $request->nama_lengkap,
                'tanggal_lahir'  => $request->tanggal_lahir,
                'jenis_kelamin'  => $request->jenis_kelamin,
                'alamat'         => $request->alamat,
            ]);

            DB::commit();
            return redirect()->route('pasien.index')->with('success', 'Data Pasien & Akun berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        // Menghapus user otomatis menghapus pasien (karena cascade onDelete di DB)
        // Atau kita hapus manual usernya
        if($pasien->id_user){
            User::destroy($pasien->id_user);
        } else {
            $pasien->delete();
        }
        
        return redirect()->route('pasien.index')->with('success', 'Data Pasien dan Akunnya berhasil dihapus!');
    }
}