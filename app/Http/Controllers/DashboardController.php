<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Resep;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // 1. DATA UNTUK DASHBOARD ADMIN
        if ($user->role === 'admin') {
            $data['jumlahObat'] = Obat::count();
            $data['jumlahPasien'] = Pasien::count();
            $data['jumlahDokter'] = Dokter::count();
            $data['jumlahPenjualan'] = Resep::sum('total_harga') ?? 0;
        } 
        
        // 2. DATA UNTUK DASHBOARD DOKTER
        elseif ($user->role === 'dokter') {
            $dokter = $user->dokter;
            if ($dokter) {
                // Berapa kali dokter ini meresepkan obat
                $data['jumlahResep'] = Resep::where('id_dokter', $dokter->id_dokter)->count();
                // Berapa banyak pasien unik yang pernah ia tangani
                $data['jumlahPasien'] = Resep::where('id_dokter', $dokter->id_dokter)->distinct('id_pasien')->count('id_pasien');
            } else {
                $data['jumlahResep'] = 0;
                $data['jumlahPasien'] = 0;
            }
        } 
        
        // 3. DATA UNTUK DASHBOARD PASIEN
        elseif ($user->role === 'pasien') {
            $pasien = $user->pasien;
            if ($pasien) {
                // Berapa kali pasien ini menerima resep
                $data['jumlahRiwayat'] = Resep::where('id_pasien', $pasien->id_pasien)->count();
                // Total tagihan dari seluruh riwayatnya
                $data['totalBiaya'] = Resep::where('id_pasien', $pasien->id_pasien)->sum('total_harga') ?? 0;
            } else {
                $data['jumlahRiwayat'] = 0;
                $data['totalBiaya'] = 0;
            }
        }

        return view('dashboard.index', compact('data'));
    }
}