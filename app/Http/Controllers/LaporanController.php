<?php

namespace App\Http\Controllers;

use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar: Kelompokkan berdasarkan tanggal, hitung jumlah transaksi & total uang
        $query = Resep::select(
                    'tanggal_resep',
                    DB::raw('count(id_resep) as jumlah_transaksi'),
                    DB::raw('sum(total_harga) as total_pendapatan')
                )
                ->groupBy('tanggal_resep')
                ->orderBy('tanggal_resep', 'desc');

        // Jika user melakukan filter berdasarkan rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_resep', [$request->start_date, $request->end_date]);
        }

        $laporan = $query->get();

        // Hitung Grand Total (Total Keseluruhan dari hasil filter)
        $grandTotal = $laporan->sum('total_pendapatan');

        return view('laporan.index', compact('laporan', 'grandTotal'));
    }
}