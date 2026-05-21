@extends('layouts.app')
@section('title', 'Manajemen User')

@section('content')
<div class="bg-card border border-bordercolor rounded-lg shadow-sm">
    <div class="p-5 border-b border-bordercolor bg-mainbg rounded-t-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-title">Daftar Akun Pengguna</h3>
                <p class="text-sm text-textsec mt-1">Cari user berdasarkan username atau role.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full md:w-auto">
                <form action="{{ route('users.index') }}" method="GET" class="flex w-full sm:w-auto gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..." class="w-full sm:w-72 border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary text-sm" />
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded text-sm font-semibold">Cari</button>
                </form>
                <button onclick="openModal('modalTambahUser')" class="bg-primary hover:bg-primary-hover text-white px-4 py-2 rounded shadow-sm text-sm font-bold transition">
                    + Tambah User Terpadu
                </button>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto p-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-bordercolor text-textsec text-sm uppercase">
                    <th class="py-3 px-4 font-bold">
                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort_by' => 'id_user', 'sort_direction' => $currentSort['by'] == 'id_user' && $currentSort['direction'] == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition inline-flex items-center gap-1">
                            ID
                            @if($currentSort['by'] == 'id_user')
                                <span class="text-xs">{{ $currentSort['direction'] == 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 font-bold">
                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort_by' => 'username', 'sort_direction' => $currentSort['by'] == 'username' && $currentSort['direction'] == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition inline-flex items-center gap-1">
                            Username
                            @if($currentSort['by'] == 'username')
                                <span class="text-xs">{{ $currentSort['direction'] == 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 font-bold">
                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort_by' => 'role', 'sort_direction' => $currentSort['by'] == 'role' && $currentSort['direction'] == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition inline-flex items-center gap-1">
                            Role Akses
                            @if($currentSort['by'] == 'role')
                                <span class="text-xs">{{ $currentSort['direction'] == 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 font-bold">
                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort_by' => 'is_active', 'sort_direction' => $currentSort['by'] == 'is_active' && $currentSort['direction'] == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition inline-flex items-center gap-1">
                            Status
                            @if($currentSort['by'] == 'is_active')
                                <span class="text-xs">{{ $currentSort['direction'] == 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 font-bold">
                        <a href="{{ route('users.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_direction' => $currentSort['by'] == 'created_at' && $currentSort['direction'] == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-primary transition inline-flex items-center gap-1">
                            Tgl Dibuat
                            @if($currentSort['by'] == 'created_at')
                                <span class="text-xs">{{ $currentSort['direction'] == 'asc' ? '▲' : '▼' }}</span>
                            @endif
                        </a>
                    </th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-textmain text-sm">
                @forelse($users as $item)
                <tr class="border-b border-bordercolor hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-semibold text-textsec">#{{ $item->id_user }}</td>
                    <td class="py-3 px-4 font-bold">{{ $item->username }}</td>
                    <td class="py-3 px-4">
                        @if($item->role == 'admin')
                            <span class="bg-primary/20 text-sidebar px-2 py-1 rounded-full text-xs font-bold uppercase">Admin</span>
                        @elseif($item->role == 'dokter')
                            <span class="bg-warning/20 text-warning px-2 py-1 rounded-full text-xs font-bold uppercase">Dokter</span>
                        @else
                            <span class="bg-info/20 text-info px-2 py-1 rounded-full text-xs font-bold uppercase">Pasien</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($item->is_active)
                            <span class="bg-success/20 text-success px-2 py-1 rounded-full text-xs font-bold">Aktif</span>
                        @else
                            <span class="bg-danger/20 text-danger px-2 py-1 rounded-full text-xs font-bold">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3 px-4 flex justify-center space-x-2">
                        <button onclick="openModal('modalEditUser{{ $item->id_user }}')" class="bg-info hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Edit Login</button>
                        @if(Auth::id() != $item->id_user)
                            <form action="{{ route('users.toggle-status', $item->id_user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="{{ $item->is_active ? 'bg-warning hover:bg-yellow-600' : 'bg-success hover:bg-green-600' }} text-white px-3 py-1.5 rounded text-xs font-semibold">
                                    {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('users.destroy', $item->id_user) }}" method="POST" class="form-confirm" data-title="Hapus Hak Akses?" data-message="Menghapus user akan menghilangkan akses login mereka ke dalam sistem. Lanjutkan?" data-confirm-text="Ya, Cabut Akses!">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-danger hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs font-semibold" {{ Auth::id() == $item->id_user ? 'disabled' : '' }}>Hapus</button>
                        </form>
                        <a href="{{ route('users.show', $item->id_user) }}" class="bg-slate-500 hover:bg-slate-600 text-white px-3 py-1.5 rounded text-xs font-semibold">Detail</a>
                    </td>
                </tr>

                <!-- MODAL EDIT LOGIN SAJA -->
                <div id="modalEditUser{{ $item->id_user }}" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
                    <div class="bg-card w-full max-w-md rounded-lg shadow-xl border border-bordercolor overflow-hidden">
                        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg">
                            <h3 class="font-bold text-title">Edit Kredensial Login</h3>
                            <button type="button" onclick="closeModal('modalEditUser{{ $item->id_user }}')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('users.update', $item->id_user) }}" method="POST" class="space-y-4">
                                @csrf @method('PUT')
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Username</label>
                                    <input type="text" name="username" value="{{ $item->username }}" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Password Baru</label>
                                    <input type="password" name="password" placeholder="(Kosongkan jika tidak ingin diubah)" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold mb-1">Role</label>
                                    <input type="text" value="{{ strtoupper($item->role) }}" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-200 text-gray-600 cursor-not-allowed" readonly title="Role tidak dapat diubah demi menjaga integritas data profil">
                                    <p class="text-xs text-textsec mt-1">*Role dikunci. Hapus dan buat baru jika ingin mengganti role.</p>
                                </div>
                                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                                    <button type="button" onclick="closeModal('modalEditUser{{ $item->id_user }}')" class="bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded font-bold">Batal</button>
                                    <button type="submit" class="bg-warning hover:bg-yellow-600 text-white px-4 py-2 rounded font-bold">Update Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END MODAL EDIT -->

                @empty
                <tr><td colspan="6" class="py-6 text-center text-textsec italic">Belum ada data user.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-bordercolor bg-mainbg flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
        <div class="text-textsec">Menampilkan {{ $users->firstItem() ?? 0 }} sampai {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user</div>
        <div class="mt-2 md:mt-0">{{ $users->links() }}</div>
    </div>
</div>

<!-- ========================================= -->
<!-- MODAL TAMBAH USER TERPADU (DINAMIS) -->
<!-- ========================================= -->
<div id="modalTambahUser" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex justify-center items-center">
    <div class="bg-card w-full max-w-2xl rounded-lg shadow-xl border border-bordercolor overflow-hidden max-h-[95vh] flex flex-col">
        <div class="p-4 border-b border-bordercolor flex justify-between items-center bg-mainbg shrink-0">
            <h3 class="font-bold text-title">Form Registrasi User & Profil</h3>
            <button type="button" onclick="closeModal('modalTambahUser')" class="text-textsec hover:text-danger font-bold text-xl">&times;</button>
        </div>
        <div class="p-4 overflow-y-auto grow">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- 1. DATA LOGIN (SELALU MUNCUL) -->
                <div class="bg-gray-50 p-4 rounded-lg border border-bordercolor">
                    <h4 class="font-bold text-sm text-primary mb-3 uppercase tracking-wider">Kredensial Login</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Username Baru</label>
                            <input type="text" name="username" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Password</label>
                            <input type="password" name="password" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary" required>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-semibold mb-1">Pilih Tipe Akses (Role)</label>
                        <select name="role" id="roleSelect" onchange="toggleProfilForm()" class="w-full border border-bordercolor rounded px-3 py-2 font-bold focus:ring-primary bg-white" required>
                            <option value="">-- Pilih Tipe User --</option>
                            <option value="admin">Admin / Manajemen</option>
                            <option value="dokter">Dokter Pemeriksa</option>
                            <option value="pasien">Pasien Rumah Sakit</option>
                        </select>
                    </div>
                </div>

                <!-- 2. FORM PROFIL ADMIN (DINAMIS) -->
                <div id="form-admin" class="hidden border border-primary/40 rounded-lg p-4 bg-primary/5 transition-all">
                    <h4 class="font-bold text-sm text-primary mb-3">Lengkapi Profil Admin</h4>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Lengkap Admin</label>
                        <input type="text" id="input_nama_admin" name="nama_admin" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                    </div>
                </div>

                <!-- 3. FORM PROFIL DOKTER (DINAMIS) -->
                <div id="form-dokter" class="hidden border border-warning/40 rounded-lg p-4 bg-warning/5 transition-all">
                    <h4 class="font-bold text-sm text-warning mb-3">Lengkapi Profil Dokter</h4>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Nama Dokter (beserta Gelar)</label>
                        <input type="text" id="input_nama_dokter" name="nama_dokter" placeholder="Contoh: dr. Budi" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary mb-3">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Spesialisasi</label>
                            <input type="text" id="input_spesialisasi" name="spesialisasi" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">No. Telp</label>
                            <input type="text" name="no_telp" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                        </div>
                    </div>
                </div>

                <!-- 4. FORM PROFIL PASIEN (DINAMIS) -->
                <div id="form-pasien" class="hidden border border-info/40 rounded-lg p-4 bg-info/5 transition-all">
                    <h4 class="font-bold text-sm text-info mb-3">Lengkapi Profil Pasien</h4>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">No. Rekam Medis</label>
                            <!-- RM Dibuat Otomatis, hapus id & name atribut -->
                            <input type="text" value="Dibuat Otomatis Sistem" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-200 text-gray-500 font-bold text-sm cursor-not-allowed" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Nama Lengkap Pasien</label>
                            <input type="text" id="input_nama_pasien" name="nama_lengkap" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Tanggal Lahir</label>
                            <input type="date" id="input_tgl_lahir" name="tanggal_lahir" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                            <select id="input_jk" name="jenis_kelamin" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Alamat Domisili</label>
                        <textarea name="alamat" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-primary"></textarea>
                    </div>
                </div>

                <div class="pt-4 border-t border-bordercolor text-right space-x-2">
                    <button type="button" onclick="closeModal('modalTambahUser')" class="bg-gray-300 hover:bg-gray-400 text-textmain px-4 py-2 rounded font-bold">Batal</button>
                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded font-bold">Simpan User Terpadu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

    // ==========================================
    // LOGIKA FORM DINAMIS BERDASARKAN ROLE
    // ==========================================
    function toggleProfilForm() {
        const role = document.getElementById('roleSelect').value;
        
        // 1. Sembunyikan Semua Form Profil
        document.getElementById('form-admin').classList.add('hidden');
        document.getElementById('form-dokter').classList.add('hidden');
        document.getElementById('form-pasien').classList.add('hidden');

        // 2. Hilangkan 'Required' 
        document.getElementById('input_nama_admin').required = false;
        document.getElementById('input_nama_dokter').required = false;
        document.getElementById('input_spesialisasi').required = false;
        document.getElementById('input_nama_pasien').required = false;
        document.getElementById('input_tgl_lahir').required = false;
        document.getElementById('input_jk').required = false;
        // Catatan: input_no_rm sudah dihapus dari validasi JS karena Readonly

        // 3. Tampilkan & Aktifkan 'Required' sesuai Role
        if (role === 'admin') {
            document.getElementById('form-admin').classList.remove('hidden');
            document.getElementById('input_nama_admin').required = true;
        } 
        else if (role === 'dokter') {
            document.getElementById('form-dokter').classList.remove('hidden');
            document.getElementById('input_nama_dokter').required = true;
            document.getElementById('input_spesialisasi').required = true;
        } 
        else if (role === 'pasien') {
            document.getElementById('form-pasien').classList.remove('hidden');
            document.getElementById('input_nama_pasien').required = true;
            document.getElementById('input_tgl_lahir').required = true;
            document.getElementById('input_jk').required = true;
        }
    }
</script>
@endsection