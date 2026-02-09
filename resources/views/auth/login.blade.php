<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - DailyLog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen">
        
        <!-- BAGIAN KIRI: Visual & Branding -->
        <!-- Hidden di HP, Muncul di Layar Besar (lg) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-gray-900 items-center justify-center overflow-hidden">
            
            <!-- Gambar Background -->
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105" 
                 style="background-image: url('{{ asset('img/DSC05679.jpg') }}');">
            </div>
            
            <!-- Gradient Overlay (Agar teks terbaca) -->
            <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/50 to-black/30"></div>

            <!-- Dekorasi Animasi -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-purple-500/30 rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/30 rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 2s"></div>
            </div>

            <!-- Konten Branding (Glass Effect) -->
            <div class="relative z-10 p-12 w-full max-w-lg">
                <div class="glass-effect rounded-3xl p-8 text-center shadow-2xl">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/20 mb-6 shadow-inner border border-white/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-2 tracking-tight">DailyLog.</h1>
                    <p class="text-indigo-100 text-lg font-light leading-relaxed">
                        Sistem Laporan & Monitoring Aktivitas Magang yang Terintegrasi.
                    </p>
                </div>
            </div>
            
            <!-- Footer Kecil Kiri -->
            <div class="absolute bottom-8 text-indigo-200 text-xs font-medium">
                &copy; {{ date('Y') }} DailyLog System. All rights reserved.
            </div>
        </div>

        <!-- BAGIAN KANAN: Form Login -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-8 bg-white relative">
            
            <!-- Header Mobile (Hanya muncul di HP) -->
            <div class="lg:hidden absolute top-8 left-8">
                <h1 class="text-2xl font-bold text-indigo-600">DailyLog.</h1>
            </div>

            <div class="w-full max-w-[400px]">
                
                <div class="mb-10">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Selamat Datang! 👋</h2>
                    <p class="text-gray-500">Masukan kredensial Anda untuk mengakses dashboard.</p>
                </div>

                <!-- Session Status Error/Success -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-700 border border-green-200 text-sm font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input type="email" name="email" required autofocus
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none sm:text-sm"
                                placeholder="nama@instansi.com">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Input Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-gray-700">Password</label>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" name="password" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-900 placeholder-gray-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 outline-none sm:text-sm"
                                placeholder="••••••••">
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-600">Ingat saya</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3.5 rounded-xl transition-all duration-200 shadow-lg shadow-indigo-500/30 transform active:scale-95">
                        Masuk Dashboard
                    </button>

                </form>
                
                <!-- Footer Form -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Belum punya akun? <span class="text-gray-800 font-medium">Hubungi Mentor.</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>