@extends('layouts.app')
@section('title', 'Data Pasien')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    <div class="p-5 border-b border-bordercolor bg-mainbg rounded-t-lg">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-title">Daftar Rekam Medis Pasien</h3>
                <p class="text-sm text-textsec mt-1">Cari pasien berdasarkan No. RM, nama, atau username akun.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full lg:w-auto">
                <form action="{{ route('pasien.index') }}" method="GET" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pasien..." class="w-full sm:w-72 border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary text-sm" />
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-semibold">Cari</button>
                </form>
                <button onclick="openModal('modalTambahPasien')" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded shadow-sm text-sm font-bold transition">
                    + Tambah Pasien Baru
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">No. RM</th>
                    <th class="py-3 px-4 font-bold">Nama Pasien</th>
                    <th class="py-3 px-4 font-bold">Akun Login</th>
                    <th class="py-3 px-4 font-bold">Tanggal Lahir</th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @forelse($pasien as $item)
                <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-semibold text-info">{{ $item->no_rekam_medis }}</td>
                    <td class="py-3 px-4 font-bold">{{ $item->nama_lengkap }}</td>
                    <td class="py-3 px-4 text-xs font-semibold text-sidebar">{{ $item->user->username ?? 'Tidak Ada' }}</td>
                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d M Y') }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <button onclick="openModal('modalEditPasien{{ $item->id_pasien }}')" class="bg-info hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Edit</button>
                        <form action="{{ route('pasien.destroy', $item->id_pasien) }}" method="POST" class="form-confirm" data-title="Hapus Pasien?" data-message="Hapus data pasien ini beserta akun loginnya secara permanen?" data-confirm-text="Ya, Hapus Pasien!">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Hapus</button>
                        </form>
                        <a href="{{ route('pasien.show', $item->id_pasien) }}" class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Detail</a>
                    </td>
                </tr>

                <!-- MODAL EDIT PASIEN -->
                <div id="modalEditPasien{{ $item->id_pasien }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
                    <div class="bg-card w-full max-w-2xl rounded-lg shadow-xl border border-bordercolor overflow-hidden h-[90vh] flex flex-col">
                        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg shrink-0">
                            <h3 class="font-bold text-title">Edit Pasien: {{ $item->nama_lengkap }}</h3>
                            <button type="button" onclick="closeModal('modalEditPasien{{ $item->id_pasien }}')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
                        </div>
                        <div class="p-4 overflow-y-auto grow">
                            <form action="{{ route('pasien.update', $item->id_pasien) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                
                                <!-- BAGIAN AKUN -->
                                <div class="bg-gray-50 p-3 rounded border border-bordercolor mb-4">
                                    <h4 class="font-bold text-sm text-primary mb-2 border-b border-bordercolor pb-1">Data Akun Login</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1">Username</label>
                                            <input type="text" name="username" value="{{ $item->user->username ?? '' }}" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1">Password (Kosongkan jika tidak ubah)</label>
                                            <input type="password" name="password" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary">
                                        </div>
                                    </div>
                                </div>

                                <!-- BAGIAN PROFIL -->
                                <h4 class="font-bold text-sm text-primary mb-2 border-b border-bordercolor pb-1">Data Rekam Medis</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">No. Rekam Medis</label>
                                        <input type="text" name="no_rekam_medis" value="{{ $item->no_rekam_medis }}" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-200 text-gray-600 font-semibold cursor-not-allowed" readonly title="Nomor RM tidak bisa diubah">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="{{ $item->nama_lengkap }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">Tanggal Lahir</label>
                                        <input type="date" name="tanggal_lahir" value="{{ $item->tanggal_lahir }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                            <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                                    <textarea name="alamat" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">{{ $item->alamat }}</textarea>
                                </div>
                                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                                    <button type="button" onclick="closeModal('modalEditPasien{{ $item->id_pasien }}')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                                    <button type="submit" class="bg-warning hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END MODAL EDIT -->

                @empty
                <tr><td colspan="5" class="py-6 text-center text-textsec italic">Belum ada data pasien.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-bordercolor bg-mainbg flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
        <div class="text-textsec">Menampilkan {{ $pasien->firstItem() ?? 0 }} sampai {{ $pasien->lastItem() ?? 0 }} dari {{ $pasien->total() }} pasien</div>
        <div class="mt-2 md:mt-0">{{ $pasien->links() }}</div>
    </div>
</div>

<!-- MODAL TAMBAH PASIEN -->
<div id="modalTambahPasien" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
    <div class="bg-card w-full max-w-2xl rounded-lg shadow-xl border border-bordercolor overflow-hidden h-[90vh] flex flex-col">
        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg shrink-0">
            <h3 class="font-bold text-title">Tambah Pasien & Akun Baru</h3>
            <button type="button" onclick="closeModal('modalTambahPasien')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto grow">
            <form action="{{ route('pasien.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- BAGIAN AKUN -->
                <div class="bg-gray-50 p-3 rounded border border-bordercolor mb-4">
                    <h4 class="font-bold text-sm text-primary mb-2 border-b border-bordercolor pb-1">Buat Akun Login</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Username Baru</label>
                            <input type="text" name="username" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Password Default</label>
                            <input type="password" name="password" class="w-full border border-bordercolor rounded px-3 py-2 text-sm focus:ring-primary" required>
                        </div>
                    </div>
                </div>

                <!-- BAGIAN PROFIL -->
                <h4 class="font-bold text-sm text-primary mb-2 border-b border-bordercolor pb-1">Data Rekam Medis</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">No. Rekam Medis</label>
                        <!-- RM DIGENERATE OTOMATIS, INPUT DIMATIKAN -->
                        <input type="text" value="Dibuat Otomatis Sistem" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-200 text-gray-500 text-sm font-bold cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                            <option value="">-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Alamat</label>
                    <textarea name="alamat" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary"></textarea>
                </div>
                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                    <button type="button" onclick="closeModal('modalTambahPasien')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
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