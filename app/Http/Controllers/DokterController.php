<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DokterController extends Controller
{
    public function index()
    {
        $dokter = Dokter::with('user')->orderBy('created_at', 'desc')->get();
        return view('dokter.index', compact('dokter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'password'     => 'required|string|min:4',
            'nama_dokter'  => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:50',
            'no_telp'      => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role'     => 'dokter',
            ]);

            Dokter::create([
                'id_user'      => $user->id_user,
                'nama_dokter'  => $request->nama_dokter,
                'spesialisasi' => $request->spesialisasi,
                'no_telp'      => $request->no_telp,
            ]);

            DB::commit();
            return redirect()->route('dokter.index')->with('success', 'Akun Dokter berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username,'.$dokter->id_user.',id_user',
            'nama_dokter'  => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $userData = ['username' => $request->username];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            User::where('id_user', $dokter->id_user)->update($userData);

            $dokter->update([
                'nama_dokter'  => $request->nama_dokter,
                'spesialisasi' => $request->spesialisasi,
                'no_telp'      => $request->no_telp,
            ]);

            DB::commit();
            return redirect()->route('dokter.index')->with('success', 'Data Dokter berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }
    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        if($dokter->id_user) {
            User::destroy($dokter->id_user);
        } else {
            $dokter->delete();
        }
        return redirect()->route('dokter.index')->with('success', 'Dokter beserta akunnya dihapus!');
    }
}