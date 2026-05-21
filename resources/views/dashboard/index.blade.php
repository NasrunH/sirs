<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRS Medika - Login</title>
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
<body class="bg-mainbg flex items-center justify-center min-h-screen">
    
    <div class="bg-card w-full max-w-md p-8 rounded-xl shadow-lg border border-bordercolor">
        
        <div class="text-center mb-8">
            <div class="inline-block bg-primary/10 p-3 rounded-full mb-3">
                <!-- Icon sederhana menggunakan SVG -->
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-title">Login Sistem SIRS</h2>
            <p class="text-sm text-gray-500 mt-1">Masukkan kredensial Anda untuk melanjutkan</p>
        </div>
        
        <!-- Menampilkan pesan sukses (misal: setelah logout) -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Menampilkan pesan error dari AuthController -->
        @if($errors->has('login_failed'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                {{ $errors->first('login_failed') }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label for="username" class="block text-sm font-semibold text-textmain mb-1">Username</label>
                <!-- onlyInput('username') dari controller akan masuk ke fungsi old('username') -->
                <input type="text" id="username" name="username" value="{{ old('username') }}" 
                       class="w-full border border-bordercolor rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('username') border-red-500 @enderror" 
                       placeholder="Masukkan username Anda" required autofocus>
                @error('username')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="password" class="block text-sm font-semibold text-textmain mb-1">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full border border-bordercolor rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all @error('password') border-red-500 @enderror" 
                       placeholder="••••••••" required>
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-bold py-3 px-4 rounded-lg transition-colors shadow-sm">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="mt-6 text-center text-xs text-gray-400">
            &copy; 2026 Sistem Informasi Rumah Sakit.
        </div>
    </div>

</body>
</html>