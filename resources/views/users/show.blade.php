@extends('layouts.app')
@section('title', 'Detail Akun Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('users.index') }}" class="text-primary font-semibold hover:underline">&larr; Kembali ke Daftar Akun</a>
    </div>

    <div class="bg-card border border-bordercolor rounded-lg shadow-sm overflow-hidden">
        
        <!-- Header Akun -->
        <div class="p-6 border-b border-bordercolor bg-mainbg flex flex-col items-center justify-center text-center">
            <div class="h-20 w-20 bg-sidebar text-white rounded-full flex items-center justify-center text-3xl font-black mb-3 shadow-inner">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
            <h2 class="text-2xl font-bold text-title">{{ $user->username }}</h2>
            
            <div class="mt-2">
                @if($user->role == 'admin')
                    <span class="bg-primary/20 text-sidebar px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Hak Akses: Admin</span>
                @elseif($user->role == 'dokter')
                    <span class="bg-warning/20 text-warning px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Hak Akses: Dokter</span>
                @else
                    <span class="bg-info/20 text-info px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Hak Akses: Pasien</span>
                @endif
            </div>
        </div>

        <!-- Detail Profil Terhubung -->
        <div class="p-6">
            <h4 class="text-sm text-textsec uppercase font-bold mb-4 border-b border-bordercolor pb-2">Profil Terhubung Sistem</h4>
            
            <div class="bg-gray-50 rounded-lg p-5 border border-bordercolor">
                @if($user->role == 'admin' && $user->admin)
                    <div class="grid grid-cols-2 gap-4">
                        <div><p class="text-xs text-textsec">Nama Administrator</p><p class="font-bold text-title">{{ $user->admin->nama_admin }}</p></div>
                    </div>
                
                @elseif($user->role == 'dokter' && $user->dokter)
                    <div class="grid grid-cols-2 gap-4">
                        <div><p class="text-xs text-textsec">Nama Dokter</p><p class="font-bold text-title">{{ $user->dokter->nama_dokter }}</p></div>
                        <div><p class="text-xs text-textsec">Spesialisasi</p><p class="font-bold text-title">{{ $user->dokter->spesialisasi }}</p></div>
                        <div><p class="text-xs text-textsec">No. Telepon</p><p class="font-bold text-title">{{ $user->dokter->no_telp ?? '-' }}</p></div>
                    </div>

                @elseif($user->role == 'pasien' && $user->pasien)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><p class="text-xs text-textsec">Nama Lengkap Pasien</p><p class="font-bold text-title">{{ $user->pasien->nama_lengkap }}</p></div>
                        <div><p class="text-xs text-textsec">No. Rekam Medis</p><p class="font-bold text-info">{{ $user->pasien->no_rekam_medis }}</p></div>
                        <div><p class="text-xs text-textsec">Jenis Kelamin</p><p class="font-bold text-title">{{ $user->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p></div>
                        <div><p class="text-xs text-textsec">Tanggal Lahir</p><p class="font-bold text-title">{{ \Carbon\Carbon::parse($user->pasien->tanggal_lahir)->format('d F Y') }}</p></div>
                        <div class="md:col-span-2"><p class="text-xs text-textsec">Alamat Domisili</p><p class="font-bold text-title">{{ $user->pasien->alamat ?? '-' }}</p></div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-danger font-semibold italic">Peringatan: Akun ini tidak terikat dengan profil identitas manapun di database.</p>
                    </div>
                @endif
            </div>

            <div class="mt-6 border-t border-bordercolor pt-4 flex justify-between text-xs text-textsec">
                <p>Akun Didaftarkan Pada: {{ $user->created_at->format('d F Y, H:i') }}</p>
                <p>Status: Aktif</p>
            </div>
        </div>

    </div>
</div>
@endsection