@extends('layouts.app')
@section('title', 'Rekam Medis Pasien')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-4 flex justify-between items-center">
        <a href="{{ route('pasien.index') }}" class="text-primary font-semibold hover:underline">&larr; Kembali ke Daftar Pasien</a>
    </div>

    <!-- Profil Header -->
    <div class="bg-card border border-bordercolor rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-bordercolor bg-mainbg flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-info/20 text-info p-4 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-title">{{ $pasien->nama_lengkap }}</h2>
                    <p class="text-textsec font-semibold mt-1">Nomor RM: <span class="text-info font-bold">{{ $pasien->no_rekam_medis }}</span></p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-textsec uppercase font-bold">Akun Login Pasien</p>
                <p class="text-lg font-bold text-sidebar">{{ $pasien->user->username ?? 'Tidak Memiliki Akun' }}</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs text-textsec uppercase font-bold">Jenis Kelamin</p>
                <p class="font-semibold text-title">{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <p class="text-xs text-textsec uppercase font-bold">Tanggal Lahir (Usia)</p>
                <p class="font-semibold text-title">
                    {{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d F Y') }} 
                    <span class="text-primary">({{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->age }} Thn)</span>
                </p>
            </div>
            <div>
                <p class="text-xs text-textsec uppercase font-bold">Alamat Lengkap</p>
                <p class="font-semibold text-title">{{ $pasien->alamat ?? 'Tidak ada data alamat.' }}</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Kunjungan / Resep Terakhir -->
    <h3 class="text-lg font-bold text-title mb-4">Riwayat Peresepan Terakhir</h3>
    <div class="bg-card border border-bordercolor rounded-lg shadow-sm p-4">
        @if($pasien->resep->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-textsec uppercase border-b border-bordercolor">
                            <th class="pb-2 font-bold px-2">Tanggal</th>
                            <th class="pb-2 font-bold px-2">ID Resep</th>
                            <th class="pb-2 font-bold px-2">Dokter Pemeriksa</th>
                            <th class="pb-2 font-bold px-2 text-right">Total Tagihan</th>
                            <th class="pb-2 font-bold px-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-textmain">
                        @foreach($pasien->resep->sortByDesc('tanggal_resep')->take(5) as $resep)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-2 font-semibold">{{ \Carbon\Carbon::parse($resep->tanggal_resep)->format('d M Y') }}</td>
                            <td class="py-3 px-2">#RSP-{{ str_pad($resep->id_resep, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-2">{{ $resep->dokter->nama_dokter ?? 'Unknown' }}</td>
                            <td class="py-3 px-2 text-right font-bold text-success">Rp {{ number_format($resep->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-2 text-center">
                                <a href="{{ route('resep.show', $resep->id_resep) }}" class="text-primary hover:underline font-semibold text-xs">Lihat Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($pasien->resep->count() > 5)
                <p class="text-xs text-center text-textsec mt-4 italic">*Menampilkan 5 riwayat terakhir. Buka menu Laporan untuk data lengkap.</p>
            @endif
        @else
            <div class="text-center py-6 text-textsec italic">
                Pasien ini belum memiliki riwayat peresepan obat.
            </div>
        @endif
    </div>
</div>
@endsection