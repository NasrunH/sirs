@extends('layouts.app')
@section('title', 'Data Transaksi Peresepan')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    
    <!-- HEADER & FORM PENCARIAN -->
    <div class="p-5 border-b border-bordercolor flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-mainbg rounded-t-lg">
        <h3 class="text-lg font-bold text-title whitespace-nowrap">Daftar Resep Keluar</h3>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <!-- Form Search -->
            <form action="{{ route('resep.index') }}" method="GET" class="flex items-center w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pasien, Dokter, Tgl..." class="w-full border border-bordercolor rounded-l-lg pl-3 pr-8 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none">
                    @if(request('search'))
                        <a href="{{ route('resep.index') }}" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-danger" title="Hapus Pencarian">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="bg-sidebar hover:bg-green-900 text-white px-4 py-2 rounded-r-lg text-sm font-bold transition border border-l-0 border-sidebar">Cari</button>
            </form>

            <a href="{{ route('resep.create') }}" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded-lg shadow-sm text-sm font-bold transition flex items-center gap-2 shrink-0 w-full sm:w-auto justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Resep Baru
            </a>
        </div>
    </div>
    
    <!-- TABEL DATA -->
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">Tanggal</th>
                    <th class="py-3 px-4 font-bold">Pasien</th>
                    <th class="py-3 px-4 font-bold">Dokter</th>
                    <th class="py-3 px-4 font-bold text-right">Total Nilai</th>
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
                    <td class="py-3 px-4 font-bold text-success text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <a href="{{ route('resep.show', $item->id_resep) }}" class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Nota</a>
                        @if(Auth::user()->role == 'admin')
                            <form action="{{ route('resep.destroy', $item->id_resep) }}" method="POST" class="form-confirm" data-title="Batalkan Resep?" data-message="Resep ini akan dibatalkan dan stok obat akan dikembalikan." data-confirm-text="Ya, Batalkan!">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Batalkan</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-textsec">
                        @if(request('search'))
                            <span class="italic text-danger">Tidak ditemukan resep dengan kata kunci "{{ request('search') }}".</span>
                        @else
                            <span class="italic">Belum ada transaksi resep obat.</span>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection