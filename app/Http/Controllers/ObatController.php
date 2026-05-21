<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // 1. Tampilkan Semua Data & Halaman Utama
    public function index(Request $request)
    {
        $query = Obat::orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kode_obat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nama_obat', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('kategori', 'LIKE', "%{$searchTerm}%");
            });
        }

        $obat = $query->paginate(10)->withQueryString();
        return view('obat.index', compact('obat'));
    }

    // 2. Simpan Data Baru (Dari Modal Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'kode_obat' => 'required|unique:obat,kode_obat',
            'nama_obat' => 'required|string|max:100',
            'kategori'  => 'required|string|max:50',
            'stok'      => 'required|integer|min:0',
            'harga'     => 'required|numeric|min:0',
        ]);

        Obat::create($request->all());
        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil ditambahkan!');
    }

    // 3. Update Data (Dari Modal Edit)
    public function update(Request $request, $id)
    {
        $obat = Obat::findOrFail($id);

        $request->validate([
            'kode_obat' => 'required|unique:obat,kode_obat,'.$id.',id_obat',
            'nama_obat' => 'required|string|max:100',
            'kategori'  => 'required|string|max:50',
            'stok'      => 'required|integer|min:0',
            'harga'     => 'required|numeric|min:0',
        ]);

        $obat->update($request->all());
        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil diperbarui!');
    }

    public function show($id)
    {
        $obat = Obat::findOrFail($id);
        return view('obat.show', compact('obat'));
    }

    // 4. Hapus Data
    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Data Obat berhasil dihapus!');
    }
}