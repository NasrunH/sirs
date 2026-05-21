@extends('layouts.app')
@section('title', 'Detail Obat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('obat.index') }}" class="text-primary font-semibold hover:underline">&larr; Kembali ke Daftar Obat</a>
    </div>

    <div class="bg-card border border-bordercolor rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-bordercolor bg-mainbg flex items-center gap-4">
            <div class="bg-primary/20 text-primary p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-title">{{ $obat->nama_obat }}</h2>
                <p class="text-textsec font-semibold mt-1">Kode: <span class="text-primary">{{ $obat->kode_obat }}</span></p>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-textsec uppercase font-bold">Kategori</p>
                        <p class="text-lg font-semibold text-title">{{ $obat->kategori }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-textsec uppercase font-bold">Harga Satuan</p>
                        <p class="text-xl font-black text-success">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg border border-bordercolor text-center">
                        <p class="text-sm text-textsec uppercase font-bold mb-1">Sisa Stok Saat Ini</p>
                        <p class="text-4xl font-black {{ $obat->stok > 10 ? 'text-success' : ($obat->stok > 0 ? 'text-warning' : 'text-danger') }}">
                            {{ $obat->stok }} <span class="text-lg font-normal text-textsec">Unit</span>
                        </p>
                        @if($obat->stok <= 10 && $obat->stok > 0)
                            <p class="text-xs text-warning mt-2 font-semibold">Peringatan: Stok hampir habis!</p>
                        @elseif($obat->stok == 0)
                            <p class="text-xs text-danger mt-2 font-semibold">Peringatan: Stok kosong!</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t border-bordercolor pt-4 flex justify-between text-xs text-textsec">
                <p>Ditambahkan pada: {{ $obat->created_at->format('d M Y, H:i') }}</p>
                <p>Terakhir diupdate: {{ $obat->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection