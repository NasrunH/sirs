@extends('layouts.app')
@section('title', 'Data Dokter')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    <div class="p-5 border-b border-bordercolor bg-mainbg rounded-t-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-title">Daftar Dokter Bertugas</h3>
                <p class="text-sm text-textsec mt-1">Cari dokter berdasarkan nama, spesialisasi, atau username.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                <form action="{{ route('dokter.index') }}" method="GET" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari dokter..." class="w-full sm:w-64 border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary text-sm" />
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-semibold">Cari</button>
                </form>
                <button onclick="openModal('modalTambahDokter')" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded shadow-sm text-sm font-bold transition">
                    + Tambah Dokter & Akun
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">Nama Dokter</th>
                    <th class="py-3 px-4 font-bold">Spesialisasi</th>
                    <th class="py-3 px-4 font-bold">Akun Login</th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @forelse($dokter as $item)
                <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-bold text-title">{{ $item->nama_dokter }}</td>
                    <td class="py-3 px-4"><span class="bg-accent/30 text-sidebar px-2 py-1 rounded-full text-xs font-bold">{{ $item->spesialisasi }}</span></td>
                    <td class="py-3 px-4 text-sm font-semibold">{{ $item->user->username ?? '-' }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <button onclick="openModal('modalEditDokter{{ $item->id_dokter }}')" class="bg-info hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Edit</button>
                        <form action="{{ route('dokter.destroy', $item->id_dokter) }}" method="POST" class="form-confirm" data-title="Hapus Dokter?" data-message="Hapus dokter ini beserta akun loginnya? Data tidak dapat dikembalikan." data-confirm-text="Ya, Hapus!">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Hapus</button>
                        </form>
                        <!-- Tambahkan tombol ini di dalam tag <td> Aksi pada semua index.blade.php -->
                        <a href="{{ route('dokter.show', $item->id_dokter) }}" class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Detail</a>
                    </td>
                </tr>

                <!-- MODAL EDIT DOKTER -->
                <div id="modalEditDokter{{ $item->id_dokter }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
                    <div class="bg-card w-full max-w-lg rounded-lg shadow-xl border border-bordercolor overflow-hidden">
                        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg">
                            <h3 class="font-bold text-title">Edit Dokter & Akun</h3>
                            <button type="button" onclick="closeModal('modalEditDokter{{ $item->id_dokter }}')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('dokter.update', $item->id_dokter) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                
                                <div class="bg-gray-50 p-3 rounded border border-bordercolor">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1">Username Login</label>
                                            <input type="text" name="username" value="{{ $item->user->username ?? '' }}" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1">Ubah Password</label>
                                            <input type="password" name="password" placeholder="(Kosong = tidak diubah)" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold mb-1">Nama Dokter (beserta gelar)</label>
                                    <input type="text" name="nama_dokter" value="{{ $item->nama_dokter }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">Spesialisasi</label>
                                        <input type="text" name="spesialisasi" value="{{ $item->spesialisasi }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">No. Telepon</label>
                                        <input type="text" name="no_telp" value="{{ $item->no_telp }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                                    </div>
                                </div>
                                
                                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                                    <button type="button" onclick="closeModal('modalEditDokter{{ $item->id_dokter }}')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                                    <button type="submit" class="bg-warning hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="4" class="py-6 text-center text-textsec italic">Belum ada data dokter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-bordercolor bg-mainbg flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
        <div class="text-textsec">Menampilkan {{ $dokter->firstItem() ?? 0 }} sampai {{ $dokter->lastItem() ?? 0 }} dari {{ $dokter->total() }} dokter</div>
        <div class="mt-2 md:mt-0">{{ $dokter->links() }}</div>
    </div>
</div>

<!-- MODAL TAMBAH DOKTER -->
<div id="modalTambahDokter" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
    <div class="bg-card w-full max-w-lg rounded-lg shadow-xl border border-bordercolor overflow-hidden">
        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg">
            <h3 class="font-bold text-title">Tambah Dokter & Akun Baru</h3>
            <button type="button" onclick="closeModal('modalTambahDokter')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
        </div>
        <div class="p-4">
            <form action="{{ route('dokter.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="bg-gray-50 p-3 rounded border border-bordercolor">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Username Login</label>
                            <input type="text" name="username" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Password Default</label>
                            <input type="password" name="password" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Dokter</label>
                    <input type="text" name="nama_dokter" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Spesialisasi</label>
                        <input type="text" name="spesialisasi" placeholder="Anak / Umum" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">No. Telepon</label>
                        <input type="text" name="no_telp" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                    </div>
                </div>
                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                    <button type="button" onclick="closeModal('modalTambahDokter')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded font-bold">Simpan & Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }
</script>
@endsection