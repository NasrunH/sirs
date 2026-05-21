<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIRS Medika</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16A34A',
                        'primary-hover': '#15803D',
                        secondary: '#14B8A6',
                        accent: '#86EFAC',
                        mainbg: '#F8FAFC',
                        card: '#FFFFFF',
                        sidebar: '#14532D',
                        bordercolor: '#E2E8F0',
                        title: '#1E293B',
                        textmain: '#334155',
                        textsec: '#64748B',
                        success: '#22C55E',
                        warning: '#F59E0B',
                        danger: '#EF4444',
                        info: '#3B82F6',
                    },
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'], }
                }
            }
        }
    </script>
</head>
<body class="bg-mainbg text-textmain font-sans flex h-screen overflow-hidden antialiased">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="w-64 bg-sidebar text-white flex flex-col shadow-xl z-20 shrink-0">
        <!-- Logo -->
        <div class="p-6 border-b border-primary-hover flex items-center gap-3">
            <div class="bg-accent text-sidebar p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-accent tracking-wide">SIRS Medika</h1>
                <p class="text-xs text-gray-300 capitalize">Role: {{ Auth::user()->role ?? 'Guest' }}</p>
            </div>
        </div>

        <!-- Menu Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            @if(Auth::user()->role === 'admin')
                <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Master Data</p></div>
                <a href="{{ route('pasien.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('pasien.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Data Pasien</span></a>
                <a href="{{ route('dokter.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('dokter.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Data Dokter</span></a>
                <a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('obat.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Manajemen Obat</span></a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('users.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Manajemen User</span></a>
            @endif

            @if(in_array(Auth::user()->role, ['admin', 'dokter']))
                <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Transaksi</p></div>
                <a href="{{ route('resep.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('resep.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Peresepan Obat</span></a>
            @endif

            @if(Auth::user()->role === 'admin')
                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('laporan.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Laporan Pendapatan</span></a>
            @endif

            @if(Auth::user()->role === 'pasien')
                <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Rekam Medis</p></div>
                <a href="{{ route('pasien.riwayat') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg {{ request()->routeIs('pasien.riwayat') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}"><span class="font-medium text-sm">Riwayat Resep Saya</span></a>
            @endif
        </nav>

        <!-- Logout Area dengan Form Confirm -->
        <div class="p-4 border-t border-primary-hover bg-sidebar">
            <form action="{{ route('logout') }}" method="POST" class="form-confirm" data-title="Konfirmasi Logout" data-message="Apakah Anda yakin ingin keluar dari sistem?" data-confirm-text="Ya, Logout" data-confirm-color="#16A34A" data-icon="question">
                @csrf
                <button type="submit" class="w-full bg-danger/90 hover:bg-danger text-white py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-mainbg">
        <header class="bg-card h-16 shadow-sm border-b border-bordercolor flex justify-between items-center px-8 shrink-0 z-10">
            <h2 class="text-xl font-bold text-title">@yield('title')</h2>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-title leading-tight">{{ Auth::user()->username ?? 'Guest' }}</p>
                    <p class="text-xs text-textsec capitalize">{{ Auth::user()->role ?? 'Unknown' }}</p>
                </div>
                <div class="h-9 w-9 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold border border-primary/30">
                    {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                </div>
            </div>
        </header>
        
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                <!-- KONTEN DINAMIS -->
                @yield('content')
            </div>
        </div>
    </main>

    <!-- ============================================== -->
    <!-- SCRIPT OTOMATIS SWEETALERT & GLOBAL CONFIRM    -->
    <!-- ============================================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Tampilkan SweetAlert untuk Session Flash (Success/Error)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session("success") }}',
                    showConfirmButton: false,
                    timer: 2500
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session("error") }}',
                    confirmButtonColor: '#EF4444'
                });
            @endif

            // Jika ada error validasi form
            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops, ada yang salah!',
                    html: `<ul class="text-left text-sm">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                           </ul>`,
                    confirmButtonColor: '#EF4444'
                });
            @endif

            // 2. Global Listener untuk semua form dengan class 'form-confirm'
            const confirmForms = document.querySelectorAll('form.form-confirm');
            confirmForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Hentikan form dari submit langsung

                    // Ambil atribut data-* dari form, atau gunakan nilai default
                    const title = this.getAttribute('data-title') || 'Apakah Anda yakin?';
                    const message = this.getAttribute('data-message') || 'Data yang dihapus tidak dapat dikembalikan!';
                    const icon = this.getAttribute('data-icon') || 'warning';
                    const confirmText = this.getAttribute('data-confirm-text') || 'Ya, Lanjutkan!';
                    const confirmColor = this.getAttribute('data-confirm-color') || '#EF4444'; // Default merah

                    Swal.fire({
                        title: title,
                        text: message,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#9CA3AF',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true // Tombol Batal di kiri, Konfirmasi di kanan
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika klik Ya, submit form aslinya
                            this.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>