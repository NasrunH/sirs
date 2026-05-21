<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SIRS Medika</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Konfigurasi Palet Warna Tailwind -->
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
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-mainbg text-textmain font-sans flex h-screen overflow-hidden antialiased">

    <!-- SIDEBAR NAVIGATION -->
    <aside class="w-64 bg-sidebar text-white flex flex-col shadow-xl z-20 shrink-0">
        <!-- Logo & App Name -->
        <div class="p-6 border-b border-primary-hover flex items-center gap-3">
            <div class="bg-accent text-sidebar p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-accent tracking-wide">SIRS Medika</h1>
                <p class="text-xs text-gray-300 capitalize text-opacity-80">Role: {{ Auth::user()->role ?? 'Guest' }}</p>
            </div>
        </div>

        <!-- Menu Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto custom-scrollbar">
            
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <!-- Menu Khusus Admin -->
            @if(Auth::user()->role === 'admin')
                <div class="pt-4 pb-1">
                    <p class="px-4 text-xs font-semibold text-accent uppercase tracking-wider opacity-60">Master Data</p>
                </div>
                <a href="{{ route('pasien.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pasien.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Data Pasien</span>
                </a>
                <a href="{{ route('dokter.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dokter.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Data Dokter</span>
                </a>
                <a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('obat.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Manajemen Obat</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Manajemen User</span>
                </a>
            @endif

            <!-- Menu Khusus Transaksi (Admin & Dokter) -->
            @if(in_array(Auth::user()->role, ['admin', 'dokter']))
                <div class="pt-4 pb-1">
                    <p class="px-4 text-xs font-semibold text-accent uppercase tracking-wider opacity-60">Transaksi</p>
                </div>
                <a href="{{ route('resep.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('resep.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Peresepan Obat</span>
                </a>
            @endif

            <!-- Menu Laporan (Hanya Admin) -->
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('laporan.*') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Laporan Pendapatan</span>
                </a>
            @endif

            <!-- Menu Khusus Pasien -->
            @if(Auth::user()->role === 'pasien')
                <div class="pt-4 pb-1">
                    <p class="px-4 text-xs font-semibold text-accent uppercase tracking-wider opacity-60">Rekam Medis</p>
                </div>
                <a href="{{ route('pasien.riwayat') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors {{ request()->routeIs('pasien.riwayat') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' }}">
                    <span class="font-medium text-sm">Riwayat Resep Saya</span>
                </a>
            @endif

        </nav>

        <!-- Logout Area -->
        <div class="p-4 border-t border-primary-hover bg-sidebar">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-danger/90 hover:bg-danger text-white py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-mainbg">
        
        <!-- TOP HEADER -->
        <header class="bg-card h-16 shadow-sm border-b border-bordercolor flex justify-between items-center px-8 shrink-0 z-10">
            <h2 class="text-xl font-bold text-title">@yield('title')</h2>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-title leading-tight">{{ Auth::user()->username ?? 'Guest' }}</p>
                    <p class="text-xs text-textsec capitalize">{{ Auth::user()->role ?? 'Unknown' }}</p>
                </div>
                <!-- Avatar Placeholder -->
                <div class="h-9 w-9 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold border border-primary/30">
                    {{ strtoupper(substr(Auth::user()->username ?? 'U', 0, 1)) }}
                </div>
            </div>
        </header>
        
        <!-- SCROLLABLE CONTENT AREA -->
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                
                <!-- FLASH MESSAGES (Alerts) -->
                @if(session('success'))
                    <div class="bg-success/10 border-l-4 border-success text-success-700 p-4 rounded-r shadow-sm mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-danger/10 border-l-4 border-danger text-danger-700 p-4 rounded-r shadow-sm mb-6 flex items-center gap-3">
                        <svg class="w-5 h-5 text-danger" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <p class="text-sm font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-danger/10 border-l-4 border-danger text-danger-700 p-4 rounded-r shadow-sm mb-6">
                        <ul class="list-disc list-inside text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- KONTEN DINAMIS DARI MASING-MASING HALAMAN -->
                @yield('content')
                
            </div>
        </div>
    </main>

</body>
</html>