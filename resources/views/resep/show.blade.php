@extends('layouts.app')
@section('title', 'Detail Resep')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Action Bar -->
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('resep.index') }}" class="text-primary font-semibold hover:underline">&larr; Kembali ke Daftar</a>
        <button onclick="window.print()" class="bg-sidebar hover:bg-green-900 text-white px-4 py-2 rounded text-sm font-bold shadow flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Bukti Resep
        </button>
    </div>

    <!-- Kertas Nota Cetak -->
    <div class="bg-card border border-bordercolor rounded-lg shadow-md p-8" id="areaCetak">
        <!-- Kop Surat -->
        <div class="text-center border-b-2 border-primary pb-4 mb-6">
            <h1 class="text-2xl font-black text-sidebar tracking-widest uppercase">SIRS MEDIKA</h1>
            <p class="text-sm text-textsec mt-1">Sistem Informasi Peresepan Terpadu</p>
        </div>

        <!-- Info Header -->
        <div class="flex justify-between items-start mb-8 text-sm">
            <div>
                <p class="text-textsec mb-1 uppercase text-xs font-bold">Informasi Pasien:</p>
                <p class="font-bold text-title text-lg">{{ $resep->pasien->nama_lengkap }}</p>
                <p class="text-textmain">No. RM: <span class="font-semibold">{{ $resep->pasien->no_rekam_medis }}</span></p>
                <p class="text-textmain">Tgl Lahir: {{ \Carbon\Carbon::parse($resep->pasien->tanggal_lahir)->format('d M Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-textsec mb-1 uppercase text-xs font-bold">Detail Transaksi:</p>
                <p class="text-title"><span class="font-semibold">ID Resep:</span> #RSP-{{ str_pad($resep->id_resep, 4, '0', STR_PAD_LEFT) }}</p>
                <p class="text-title"><span class="font-semibold">Tanggal:</span> {{ \Carbon\Carbon::parse($resep->tanggal_resep)->format('d F Y') }}</p>
                <p class="text-title"><span class="font-semibold">Dokter:</span> {{ $resep->dokter->nama_dokter }}</p>
            </div>
        </div>

        <!-- Tabel Detail Obat -->
        <table class="w-full text-left mb-6">
            <thead>
                <tr class="bg-mainbg border-y border-bordercolor text-title text-sm uppercase">
                    <th class="py-2 px-3 font-bold">No</th>
                    <th class="py-2 px-3 font-bold">Nama Obat</th>
                    <th class="py-2 px-3 font-bold">Aturan Pakai</th>
                    <th class="py-2 px-3 font-bold text-right">Harga Satuan</th>
                    <th class="py-2 px-3 font-bold text-center">Qty</th>
                    <th class="py-2 px-3 font-bold text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @foreach($resep->detail as $index => $dtl)
                <tr class="border-b border-bordercolor/50">
                    <td class="py-3 px-3">{{ $index + 1 }}</td>
                    <td class="py-3 px-3 font-semibold">{{ $dtl->obat->nama_obat }}</td>
                    <td class="py-3 px-3 italic text-textsec">{{ $dtl->aturan_pakai }}</td>
                    <td class="py-3 px-3 text-right">Rp {{ number_format($dtl->harga_satuan, 0, ',', '.') }}</td>
                    <td class="py-3 px-3 text-center font-bold">{{ $dtl->jumlah }}</td>
                    <td class="py-3 px-3 text-right font-bold text-title">Rp {{ number_format($dtl->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Grand Total -->
        <div class="flex justify-end mb-8">
            <div class="w-full md:w-1/2 bg-gray-50 p-4 rounded-lg border-2 border-primary/30">
                <div class="flex justify-between items-center text-lg">
                    <span class="font-bold text-textsec uppercase text-sm">Total Tagihan:</span>
                    <span class="font-black text-primary text-xl">Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer TTD -->
        <div class="flex justify-between text-center text-sm pt-8">
            <div>
                <p class="text-textsec mb-12">Petugas / Apoteker</p>
                <p class="font-bold text-title underline">{{ $resep->pengelola->username }}</p>
            </div>
            <div>
                <p class="text-textsec mb-12">Dokter Pemeriksa</p>
                <p class="font-bold text-title underline">{{ $resep->dokter->nama_dokter }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #areaCetak, #areaCetak * { visibility: visible; }
        #areaCetak { position: absolute; left: 0; top: 0; width: 100%; border: none !important; box-shadow: none !important; }
    }
</style>
@endsection