@extends('layouts.app')
@section('title', 'Manajemen Obat')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    <!-- Header Tabel -->
    <div class="p-5 border-b border-bordercolor flex justify-between items-center bg-mainbg rounded-t-lg">
        <h3 class="text-lg font-bold text-title">Daftar Stok Obat</h3>
        <!-- Tombol Buka Modal Tambah -->
        <button onclick="openModal('modalTambah')" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded shadow-sm text-sm font-bold transition">
            + Tambah Obat Baru
        </button>
    </div>
    
    <!-- Tabel Data -->
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">Kode</th>
                    <th class="py-3 px-4 font-bold">Nama Obat</th>
                    <th class="py-3 px-4 font-bold">Kategori</th>
                    <th class="py-3 px-4 font-bold text-center">Stok</th>
                    <th class="py-3 px-4 font-bold">Harga</th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @forelse($obat as $item)
                <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-semibold">{{ $item->kode_obat }}</td>
                    <td class="py-3 px-4">{{ $item->nama_obat }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-secondary/10 text-secondary px-2 py-1 rounded-full text-xs font-bold">{{ $item->kategori }}</span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        @if($item->stok > 10)
                            <span class="text-success font-bold">{{ $item->stok }}</span>
                        @elseif($item->stok > 0)
                            <span class="text-warning font-bold">{{ $item->stok }}</span>
                        @else
                            <span class="text-danger font-bold">Habis</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <!-- Tombol Buka Modal Edit Spesifik Row Ini -->
                        <button onclick="openModal('modalEdit{{ $item->id_obat }}')" class="bg-info hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Edit</button>
                        
                        <form action="{{ route('obat.destroy', $item->id_obat) }}" method="POST" class="form-confirm" data-title="Hapus Obat?" data-message="Obat {{ $item->nama_obat }} akan dihapus dari sistem." data-confirm-text="Ya, Hapus Obat!">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>

                <!-- ============================== -->
                <!-- MODAL EDIT KHUSUS BARIS INI -->
                <!-- ============================== -->
                <div id="modalEdit{{ $item->id_obat }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
                    <div class="bg-card w-full max-w-lg rounded-lg shadow-xl border border-bordercolor overflow-hidden">
                        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg">
                            <h3 class="font-bold text-title">Edit Obat: {{ $item->kode_obat }}</h3>
                            <button type="button" onclick="closeModal('modalEdit{{ $item->id_obat }}')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('obat.update', $item->id_obat) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-textmain mb-1">Kode Obat</label>
                                        <input type="text" name="kode_obat" value="{{ $item->kode_obat }}" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-100" readonly>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-textmain mb-1">Nama Obat</label>
                                        <input type="text" name="nama_obat" value="{{ $item->nama_obat }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-textmain mb-1">Kategori</label>
                                    <select name="kategori" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                                        <option value="Tablet" {{ $item->kategori == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                        <option value="Sirup" {{ $item->kategori == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                                        <option value="Kapsul" {{ $item->kategori == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                        <option value="Salep" {{ $item->kategori == 'Salep' ? 'selected' : '' }}>Salep</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-textmain mb-1">Stok</label>
                                        <input type="number" name="stok" value="{{ $item->stok }}" min="0" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-textmain mb-1">Harga (Rp)</label>
                                        <input type="number" name="harga" value="{{ $item->harga }}" min="0" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                                    <button type="button" onclick="closeModal('modalEdit{{ $item->id_obat }}')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                                    <button type="submit" class="bg-warning hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END MODAL EDIT -->

                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-textsec italic">Belum ada data obat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ============================== -->
<!-- MODAL TAMBAH DATA (CREATE) -->
<!-- ============================== -->
<div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center transition-opacity">
    <div class="bg-card w-full max-w-lg rounded-lg shadow-xl border border-bordercolor overflow-hidden">
        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg">
            <h3 class="font-bold text-title">Tambah Obat Baru</h3>
            <button type="button" onclick="closeModal('modalTambah')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
        </div>
        <div class="p-4">
            <form action="{{ route('obat.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Kode Obat</label>
                        <input type="text" name="kode_obat" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" placeholder="OBT-001" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Nama Obat</label>
                        <input type="text" name="nama_obat" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-textmain mb-1">Kategori</label>
                    <select name="kategori" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                        <option value="">-- Pilih --</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Sirup">Sirup</option>
                        <option value="Kapsul">Kapsul</option>
                        <option value="Salep">Salep</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Stok Awal</label>
                        <input type="number" name="stok" min="0" value="0" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Harga Satuan</label>
                        <input type="number" name="harga" min="0" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary" required>
                    </div>
                </div>
                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                    <button type="button" onclick="closeModal('modalTambah')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded font-bold">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script Pembuka Tutup Modal -->
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection