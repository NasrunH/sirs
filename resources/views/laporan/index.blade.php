@extends('layouts.app')
@section('title', 'Laporan Pendapatan Harian')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    
    <!-- HEADER & FILTER -->
    <div class="p-5 border-b border-bordercolor bg-mainbg rounded-t-lg no-print">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h3 class="text-lg font-bold text-title">Rekapitulasi Penjualan</h3>
            
            <!-- Form Filter Tanggal -->
            <form action="{{ route('laporan.index') }}" method="GET" class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white border border-bordercolor rounded px-2 py-1">
                    <span class="text-xs font-semibold text-textsec">Dari:</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="text-sm focus:outline-none bg-transparent" required>
                </div>
                <div class="flex items-center gap-2 bg-white border border-bordercolor rounded px-2 py-1">
                    <span class="text-xs font-semibold text-textsec">Sampai:</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="text-sm focus:outline-none bg-transparent" required>
                </div>
                <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-1.5 rounded text-sm font-bold transition">Filter</button>
                <a href="{{ route('laporan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-textmain px-3 py-1.5 rounded text-sm font-bold transition">Reset</a>
            </form>
        </div>
    </div>

    <!-- AREA CETAK LAPORAN -->
    <div id="areaCetak" class="p-6">
        
        <!-- Judul Kop (Hanya muncul saat di-print) -->
        <div class="print-only text-center mb-6 border-b-2 border-primary pb-4 hidden">
            <h2 class="text-2xl font-black text-title uppercase">LAPORAN PENDAPATAN HARIAN</h2>
            <p class="text-textmain mt-1">Sistem Informasi Rumah Sakit (SIRS)</p>
            @if(request('start_date') && request('end_date'))
                <p class="text-sm text-textsec mt-1">Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} s/d {{ \Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}</p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-bordercolor text-title text-sm uppercase bg-gray-50">
                        <th class="py-3 px-4 font-bold">No</th>
                        <th class="py-3 px-4 font-bold">Tanggal Transaksi</th>
                        <th class="py-3 px-4 font-bold text-center">Jumlah Lembar Resep</th>
                        <th class="py-3 px-4 font-bold text-right">Total Pendapatan (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-textmain text-sm">
                    @forelse($laporan as $key => $item)
                    <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ $key + 1 }}</td>
                        <td class="py-3 px-4 font-semibold">{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d F Y') }}</td>
                        <td class="py-3 px-4 text-center font-bold text-info">{{ $item->jumlah_transaksi }} Transaksi</td>
                        <td class="py-3 px-4 text-right font-bold text-success">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-6 text-center text-textsec italic">Tidak ada data penjualan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                <!-- GRAND TOTAL -->
                @if(count($laporan) > 0)
                <tfoot>
                    <tr class="bg-primary/10 border-t-2 border-primary">
                        <td colspan="3" class="py-4 px-4 text-right font-extrabold text-sidebar uppercase text-sm">Grand Total Pendapatan:</td>
                        <td class="py-4 px-4 text-right font-black text-primary text-lg">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        <!-- Tombol Cetak Dokumen -->
        @if(count($laporan) > 0)
        <div class="mt-8 text-right no-print">
            <button onclick="window.print()" class="bg-sidebar hover:bg-green-900 text-white px-6 py-2.5 rounded font-bold shadow-sm flex items-center gap-2 inline-flex">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </button>
        </div>
        @endif
    </div>
</div>

<!-- CSS Tambahan Khusus Mode Print -->
<style>
    @media print {
        body * { visibility: hidden; }
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        #areaCetak, #areaCetak * { visibility: visible; }
        #areaCetak { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
    }
</style>
@endsection