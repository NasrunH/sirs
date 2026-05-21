<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ResepController extends Controller
{
    // 1. DAFTAR RESEP (Admin melihat semua, Dokter melihat miliknya saja)
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Memulai query dengan eager loading
        $query = Resep::with(['pasien', 'dokter', 'pengelola'])->orderBy('created_at', 'desc');

        // Jika dokter, filter hanya resep yang dia buat
        if ($user->role === 'dokter') {
            if(!$user->dokter) return back()->with('error', 'Profil Dokter belum lengkap!');
            $query->where('id_dokter', $user->dokter->id_dokter);
        }

        // ==========================================
        // LOGIKA PENCARIAN (SEARCH)
        // ==========================================
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            
            $query->where(function($q) use ($searchTerm) {
                // 1. Cari berdasarkan tanggal resep
                $q->where('tanggal_resep', 'LIKE', "%{$searchTerm}%")
                  
                  // 2. Atau cari di dalam relasi tabel pasien (Nama & RM)
                  ->orWhereHas('pasien', function($qPasien) use ($searchTerm) {
                      $qPasien->where('nama_lengkap', 'LIKE', "%{$searchTerm}%")
                              ->orWhere('no_rekam_medis', 'LIKE', "%{$searchTerm}%");
                  })
                  
                  // 3. Atau cari di dalam relasi tabel dokter (Nama)
                  ->orWhereHas('dokter', function($qDokter) use ($searchTerm) {
                      $qDokter->where('nama_dokter', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }
        // ==========================================

        $resep = $query->get();
        return view('resep.index', compact('resep'));
    }

    // 2. FORM BUAT RESEP
    public function create()
    {
        $pasien = Pasien::all();
        $obat = Obat::where('stok', '>', 0)->get(); 
        
        // Ambil data dokter, jika admin tampilkan semua, jika dokter cukup data dia sendiri
        if (Auth::user()->role === 'admin') {
            $dokter = Dokter::all();
        } else {
            $dokter = Dokter::where('id_user', Auth::id())->get();
        }
        
        return view('resep.create', compact('pasien', 'dokter', 'obat'));
    }

    // 3. PROSES SIMPAN RESEP (Dengan Transaction)
    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required',
            // Jika dokter, id_dokter kita ambil dari backend demi keamanan (mencegah inspect element)
            'id_dokter' => Auth::user()->role === 'admin' ? 'required' : 'nullable',
            'obat'      => 'required|array|min:1',
            'obat.*.id_obat' => 'required',
            'obat.*.jumlah'  => 'required|integer|min:1',
            'obat.*.aturan_pakai' => 'required|string',
        ]);

        // Keamanan: Tetapkan ID Dokter di backend jika yang login adalah dokter
        $id_dokter = Auth::user()->role === 'admin' ? $request->id_dokter : Auth::user()->dokter->id_dokter;

        DB::beginTransaction();
        try {
            // A. Simpan Header
            $resep = Resep::create([
                'tanggal_resep' => now()->toDateString(),
                'id_pasien'     => $request->id_pasien,
                'id_dokter'     => $id_dokter,
                'id_user'       => Auth::id(), // Siapa yang menginput ke sistem
                'total_harga'   => 0 
            ]);

            $total_harga = 0;

            // B. Simpan Detail & Potong Stok
            foreach ($request->obat as $item) {
                $dataObat = Obat::findOrFail($item['id_obat']);

                if ($dataObat->stok < $item['jumlah']) {
                    throw new \Exception("Stok obat {$dataObat->nama_obat} tidak cukup! (Sisa: {$dataObat->stok})");
                }

                $subtotal = $dataObat->harga * $item['jumlah'];
                $total_harga += $subtotal;

                DetailResep::create([
                    'id_resep'     => $resep->id_resep,
                    'id_obat'      => $dataObat->id_obat,
                    'jumlah'       => $item['jumlah'],
                    'harga_satuan' => $dataObat->harga,
                    'subtotal'     => $subtotal,
                    'aturan_pakai' => $item['aturan_pakai'],
                ]);

                $dataObat->decrement('stok', $item['jumlah']);
            }

            // C. Update Total Harga
            $resep->update(['total_harga' => $total_harga]);

            DB::commit();
            return redirect()->route('resep.show', $resep->id_resep)->with('success', 'Resep berhasil diterbitkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    // 4. DETAIL / NOTA RESEP
    public function show($id)
    {
        $resep = Resep::with(['pasien', 'dokter', 'pengelola', 'detail.obat'])->findOrFail($id);
        
        // Keamanan: Pastikan pasien/dokter hanya bisa melihat detail resep miliknya
        $user = Auth::user();
        if ($user->role === 'dokter' && $resep->id_dokter != $user->dokter->id_dokter) abort(403);
        if ($user->role === 'pasien' && $resep->id_pasien != $user->pasien->id_pasien) abort(403);

        return view('resep.show', compact('resep'));
    }

    // 5. HAPUS RESEP (Hanya Admin)
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya admin yang bisa membatalkan resep.');

        $resep = Resep::with('detail')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            foreach($resep->detail as $detail) {
                $obat = Obat::find($detail->id_obat);
                if($obat) $obat->increment('stok', $detail->jumlah);
            }
            $resep->delete();
            
            DB::commit();
            return redirect()->route('resep.index')->with('success', 'Resep dibatalkan. Stok obat telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan resep.');
        }
    }

    // 6. HALAMAN KHUSUS PASIEN
    public function riwayatPasien()
    {
        $user = Auth::user();
        if(!$user->pasien) return back()->with('error', 'Data Rekam Medis belum terhubung.');

        $resep = Resep::with(['dokter', 'detail.obat'])
                      ->where('id_pasien', $user->pasien->id_pasien)
                      ->orderBy('tanggal_resep', 'desc')
                      ->get();
                      
        return view('resep.riwayat', compact('resep'));
    }
}