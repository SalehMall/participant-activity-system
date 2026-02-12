@php
    $layout = in_array(Auth::user()->role, ['mentor', 'super_admin']) ? 'admin-layout' : 'app-layout';
@endphp

<x-dynamic-component :component="$layout">
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Pengaturan Akun</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- KOLOM KIRI: KARTU PROFIL -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Kartu Info User -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                        <!-- Header Gradient -->
                        <div class="h-32 bg-gradient-to-r from-indigo-600 to-purple-600"></div>

                        <div class="px-6 pb-8 relative text-center">
                            <!-- Avatar (Inisial) -->
                            <div class="w-24 h-24 mx-auto -mt-12 rounded-full bg-white p-1.5 shadow-lg">
                                <div class="w-full h-full rounded-full bg-slate-900 flex items-center justify-center text-2xl font-bold text-white uppercase">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            </div>

                            <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>

                            <div class="mt-4 flex justify-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'mentor' ? 'bg-indigo-100 text-indigo-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $user->role === 'super_admin' ? 'Super Admin' : ($user->role === 'mentor' ? 'Instruktur' : 'Peserta Magang') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Akun</h4>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Bergabung Sejak</p>
                                    <p class="font-medium text-gray-800">{{ $user->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs">Status Keamanan</p>
                                    <p class="font-medium text-green-600">Terverifikasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: FORM UPDATE -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- 1. Update Profile Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Informasi Profil</h3>
                            <p class="text-sm text-gray-500">Perbarui informasi profil akun dan alamat email Anda.</p>
                        </div>

                        <!-- Form -->
                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="pl-10 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                </div>
                                @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="pl-10 w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                </div>
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition shadow-md shadow-indigo-200">
                                    Simpan Perubahan
                                </button>
                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                                        Data berhasil disimpan.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- 2. Update Password -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Ganti Password</h3>
                            <p class="text-sm text-gray-500">Pastikan akun Anda aman dengan menggunakan password yang kuat.</p>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                    @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm">
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit" class="px-6 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition shadow-md">
                                    Update Password
                                </button>
                                @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                                        Password berhasil diubah.
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- 3. Delete Account (Danger Zone) -->
                    <div class="bg-red-50 rounded-2xl border border-red-100 p-8">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-red-800">Hapus Akun</h3>
                                <p class="text-sm text-red-600 mt-1">Setelah akun dihapus, semua data akan hilang permanen.</p>
                            </div>

                            <!-- AlpineJS Modal Logic for Deletion -->
                            <div x-data="{ open: false }">
                                <button @click="open = true" class="px-4 py-2 bg-red-600 text-white text-sm font-bold rounded-lg hover:bg-red-700 transition">
                                    Hapus Akun Saya
                                </button>

                                <!-- Modal Backdrop & Content -->
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6 mx-4">
                                        <h2 class="text-lg font-bold text-gray-900">Yakin ingin menghapus akun?</h2>
                                        <p class="mt-2 text-sm text-gray-600">
                                            Masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.
                                        </p>

                                        <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                                            @csrf
                                            @method('delete')

                                            <input type="password" name="password" placeholder="Password Anda" class="w-full rounded-lg border-gray-300 mb-4 focus:ring-red-500 focus:border-red-500">

                                            <div class="flex justify-end gap-3">
                                                <button type="button" @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Hapus Permanen</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
