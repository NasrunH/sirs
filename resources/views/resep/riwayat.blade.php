@extends('layouts.app')
@section('title', 'Riwayat Rekam Medis & Resep Saya')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-title">Riwayat Berobat</h2>
        <p class="text-textsec text-sm mt-1">Daftar obat dan rincian biaya yang pernah diresepkan untuk Anda.</p>
    </div>
    <div class="bg-primary/10 border-l-4 border-primary px-4 py-2 rounded text-primary shadow-sm">
        <p class="text-xs font-bold uppercase tracking-wider">NO. RM PASIEN</p>
        <p class="text-lg font-black tracking-widest">{{ Auth::user()->pasien->no_rekam_medis ?? '-' }}</p>
    </div>
</div>

<div class="space-y-6">
    @forelse($resep as $item)
    <div class="bg-card border border-bordercolor rounded-lg shadow-sm overflow-hidden">
        
        <!-- Header Riwayat (Tanggal & Dokter) -->
        <div class="bg-mainbg p-4 border-b border-bordercolor flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-primary text-white p-2 rounded">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-textsec font-bold uppercase">Tanggal Berobat</p>
                    <p class="font-bold text-title">{{ \Carbon\Carbon::parse($item->tanggal_resep)->format('d F Y') }}</p>
                </div>
            </div>
            
            <div class="text-right sm:text-left">
                <p class="text-xs text-textsec font-bold uppercase">Dokter Pemeriksa</p>
                <p class="font-bold text-title">{{ $item->dokter->nama_dokter ?? '-' }} <span class="text-xs font-normal text-sidebar bg-accent/30 px-2 py-0.5 rounded-full ml-1">{{ $item->dokter->spesialisasi ?? '' }}</span></p>
            </div>
            
            <div>
                <a href="{{ route('resep.show', $item->id_resep) }}" class="bg-white border-2 border-info text-info hover:bg-info hover:text-white px-4 py-1.5 rounded-full text-xs font-bold transition">
                    Cetak Nota
                </a>
            </div>
        </div>

        <!-- Detail Obat & Biaya -->
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs text-textsec uppercase border-b border-gray-100">
                            <th class="pb-2 px-2">Rincian Obat & Aturan Pakai</th>
                            <th class="pb-2 px-2 text-right">Harga Satuan</th>
                            <th class="pb-2 px-2 text-center">Qty</th>
                            <th class="pb-2 px-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($item->detail as $dtl)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                            <td class="py-3 px-2">
                                <p class="font-bold text-title">{{ $dtl->obat->nama_obat }}</p>
                                <p class="text-xs text-textsec italic flex items-center gap-1 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $dtl->aturan_pakai }}
                                </p>
                            </td>
                            <td class="py-3 px-2 text-right text-textsec font-medium">Rp {{ number_format($dtl->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 px-2 text-center font-bold">x {{ $dtl->jumlah }}</td>
                            <td class="py-3 px-2 text-right font-bold text-textmain">Rp {{ number_format($dtl->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Total Tagihan -->
        <div class="bg-gray-50 p-4 border-t border-bordercolor flex justify-between items-center">
            <span class="font-bold text-textsec uppercase text-sm">Total Tagihan Berobat:</span>
            <span class="font-black text-success text-xl">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>
    @empty
    
    <div class="bg-card border border-bordercolor rounded-lg shadow-sm p-10 text-center flex flex-col items-center justify-center">
        <div class="bg-gray-100 p-4 rounded-full mb-4">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
        <h3 class="text-lg font-bold text-title mb-1">Belum Ada Riwayat</h3>
        <p class="text-textsec text-sm max-w-md">Anda belum memiliki riwayat resep obat di rumah sakit ini. Apabila Anda baru saja berobat, mohon tunggu hingga dokter/admin menerbitkan resep Anda.</p>
    </div>
    @endforelse
</div>
@endsection