@extends('layouts.app')
@section('title', 'Profil Dokter')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('dokter.index') }}" class="text-primary font-semibold hover:underline">&larr; Kembali ke Daftar Dokter</a>
    </div>

    <div class="bg-card border border-bordercolor rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b border-bordercolor bg-mainbg flex items-center gap-4">
            <div class="bg-warning/20 text-warning p-4 rounded-full">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-title">{{ $dokter->nama_dokter }}</h2>
                <span class="inline-block mt-1 bg-warning/20 text-warning px-3 py-1 rounded-full text-xs font-bold uppercase">{{ $dokter->spesialisasi }}</span>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm text-textsec uppercase font-bold mb-4 border-b border-bordercolor pb-2">Informasi Kontak</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-textsec">Nomor Telepon</p>
                            <p class="text-base font-semibold text-title">{{ $dokter->no_telp ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm text-textsec uppercase font-bold mb-4 border-b border-bordercolor pb-2">Informasi Sistem</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-textsec">Akun Login (Username)</p>
                            <p class="text-base font-bold text-sidebar">{{ $dokter->user->username ?? 'Belum Terhubung ke Akun' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-textsec">Bergabung Sejak</p>
                            <p class="text-base font-semibold text-title">{{ $dokter->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection