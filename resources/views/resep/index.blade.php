@extends('layouts.app')
@section('title', 'Data Transaksi Peresepan')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    <div class="p-5 border-b border-bordercolor flex justify-between items-center bg-mainbg rounded-t-lg">
        <h3 class="text-lg font-bold text-title">Daftar Resep Keluar</h3>
        <a href="{{ route('resep.create') }}" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded shadow-sm text-sm font-bold transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Buat Resep Baru
        </a>
    </div>
    
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">Tanggal</th>
                    <th class="py-3 px-4 font-bold">Pasien</th>
                    <th class="py-3 px-4 font-bold">Dokter</th>
                    <th class="py-3 px-4 font-bold">Total Nilai</th>
                    <th class="py-3 px-4 font-bold">Petugas</th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @forelse($resep as $item)
                <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-semibold text-textsec">{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d M Y') }}</td>
                    <td class="py-3 px-4">
                        <p class="font-bold text-title">{{ $item->pasien->nama_lengkap ?? 'Unknown' }}</p>
                        <p class="text-xs text-info">{{ $item->pasien->no_rekam_medis ?? '-' }}</p>
                    </td>
                    <td class="py-3 px-4 font-medium">{{ $item->dokter->nama_dokter ?? 'Unknown' }}</td>
                    <td class="py-3 px-4 font-bold text-success">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 text-xs">{{ $item->pengelola->username ?? '-' }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <a href="{{ route('resep.show', $item->id_resep) }}" class="bg-info hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Detail / Nota</a>
                        @if(Auth::user()->role == 'admin')
                            <form action="{{ route('resep.destroy', $item->id_resep) }}" method="POST" class="form-confirm" data-title="Batalkan Resep?" data-message="Resep ini akan dibatalkan dan stok obat akan otomatis dikembalikan ke inventaris." data-confirm-text="Ya, Batalkan Resep!">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Batalkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-6 text-center text-textsec italic">Belum ada transaksi resep.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection