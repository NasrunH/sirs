<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRS Medika - Pendaftaran Pasien</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16A34A',
                        'primary-hover': '#15803D',
                        mainbg: '#F8FAFC',
                        card: '#FFFFFF',
                        title: '#1E293B',
                        bordercolor: '#E2E8F0',
                        textmain: '#334155'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-mainbg flex items-center justify-center min-h-screen py-10">
    
    <div class="bg-card w-full max-w-2xl p-8 rounded-xl shadow-lg border border-bordercolor mx-4">
        
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-title">Pendaftaran Pasien Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Lengkapi data diri Anda untuk membuat Nomor Rekam Medis (RM)</p>
        </div>
        
        <!-- Pesan Error Sistem -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- BAGIAN 1: DATA LOGIN -->
                <div class="space-y-4">
                    <h3 class="font-bold text-primary border-b border-bordercolor pb-2">Informasi Akun</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Username Login</label>
                        <input type="text" name="username" value="{{ old('username') }}" 
                               class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('username') border-red-500 @enderror" 
                               placeholder="tanpa_spasi" required>
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Password</label>
                        <input type="password" name="password" 
                               class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('password') border-red-500 @enderror" required>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Ulangi Password</label>
                        <input type="password" name="password_confirmation" 
                               class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary" required>
                    </div>
                </div>

                <!-- BAGIAN 2: DATA PROFIL MEDIS -->
                <div class="space-y-4">
                    <h3 class="font-bold text-primary border-b border-bordercolor pb-2">Profil Pasien</h3>
                    
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" 
                               class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary @error('nama_lengkap') border-red-500 @enderror" required>
                        @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                               class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-textmain mb-1">Jenis Kelamin</label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center text-sm">
                                <input type="radio" name="jenis_kelamin" value="L" class="mr-2 text-primary focus:ring-primary" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required> Laki-laki
                            </label>
                            <label class="flex items-center text-sm">
                                <input type="radio" name="jenis_kelamin" value="P" class="mr-2 text-primary focus:ring-primary" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required> Perempuan
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN ALAMAT -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-textmain mb-1">Alamat Tempat Tinggal</label>
                <textarea name="alamat" rows="2" 
                          class="w-full border border-bordercolor rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-primary">{{ old('alamat') }}</textarea>
            </div>
            
            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-sm">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-textmain">
            Sudah pernah mendaftar? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Login di sini</a>
        </div>
    </div>

</body>
</html>