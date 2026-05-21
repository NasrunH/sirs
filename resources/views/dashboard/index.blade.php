@extends('layouts.app')
@section('title', 'Dashboard Sistem')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-title">Selamat Datang, {{ Auth::user()->username }}!</h2>
    <p class="text-textsec mt-1">
        @if(Auth::user()->role === 'admin')
            Berikut adalah ringkasan data Sistem Informasi Rumah Sakit secara keseluruhan.
        @elseif(Auth::user()->role === 'dokter')
            Berikut adalah ringkasan aktivitas medis Anda.
        @elseif(Auth::user()->role === 'pasien')
            Berikut adalah ringkasan riwayat medis Anda di rumah sakit kami.
        @endif
    </p>
</div>

<!-- ========================================== -->
<!-- 1. TAMPILAN DASHBOARD KHUSUS ADMIN         -->
<!-- ========================================== -->
@if(Auth::user()->role === 'admin')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-primary transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-primary/10 text-primary p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahObat'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Total Obat</p>
    </div>
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-info transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-info/10 text-info p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahPasien'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Total Pasien</p>
    </div>
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-warning transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-warning/10 text-warning p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahDokter'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Dokter Aktif</p>
    </div>
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-success transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-success/10 text-success p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-extrabold text-title">Rp {{ number_format($data['jumlahPenjualan'], 0, ',', '.') }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Total Pendapatan</p>
    </div>
</div>
@endif

<!-- ========================================== -->
<!-- 2. TAMPILAN DASHBOARD KHUSUS DOKTER        -->
<!-- ========================================== -->
@if(Auth::user()->role === 'dokter')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-info transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-info/10 text-info p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahResep'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Resep Diterbitkan</p>
    </div>
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-warning transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-warning/10 text-warning p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahPasien'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Pasien Ditangani</p>
    </div>
</div>
<div class="text-center mt-4">
    <a href="{{ route('resep.index') }}" class="inline-block bg-primary hover:bg-primary-hover text-white font-bold py-2 px-6 rounded-lg transition-colors">
        Lihat Riwayat Peresepan
    </a>
</div>
@endif

<!-- ========================================== -->
<!-- 3. TAMPILAN DASHBOARD KHUSUS PASIEN        -->
<!-- ========================================== -->
@if(Auth::user()->role === 'pasien')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-primary transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-primary/10 text-primary p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-3xl font-extrabold text-title">{{ $data['jumlahRiwayat'] }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Riwayat Berobat</p>
    </div>
    <div class="bg-card rounded-lg shadow-sm border border-bordercolor p-6 flex flex-col items-center justify-center border-b-4 border-b-danger transition hover:-translate-y-1 hover:shadow-md">
        <div class="bg-danger/10 text-danger p-3 rounded-full mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-2xl font-extrabold text-title">Rp {{ number_format($data['totalBiaya'], 0, ',', '.') }}</h3>
        <p class="text-sm font-semibold text-textsec uppercase mt-1">Total Biaya Obat</p>
    </div>
</div>
<div class="bg-info/10 border-l-4 border-info p-4 rounded text-info-700">
    <p class="font-semibold text-sm">Nomor Rekam Medis Anda: <span class="font-black text-info text-lg ml-2">{{ Auth::user()->pasien->no_rekam_medis ?? '-' }}</span></p>
    <p class="text-xs mt-1">Harap sebutkan nomor RM di atas saat melakukan kunjungan berikutnya ke rumah sakit.</p>
</div>
@endif

@endsection