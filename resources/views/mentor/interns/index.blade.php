<x-admin-layout>
    <div class="space-y-6">
        
        <!-- Header: Judul & Tombol Tambah -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Data Peserta Pelatihan</h2>
                <p class="text-gray-500 text-sm">Kelola akun, edit informasi, atau hapus peserta.</p>
            </div>
            <a href="{{ route('mentor.interns.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Peserta
            </a>
        </div>

        <!-- Alert Sukses -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg flex justify-between items-center">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
            </div>
        @endif

        <!-- Card Tabel -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Search Bar -->
            <!-- Search Bar yang Diperbaiki -->
<div class="p-5 border-b border-gray-100 bg-gray-50/50">
    <form method="GET" action="{{ route('mentor.interns.index') }}" class="relative max-w-md">
        <!-- Icon Search -->
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        
        <!-- Input Field -->
        <input type="text" name="search" value="{{ request('search') }}" 
            class="block w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 shadow-sm transition-all duration-200" 
            placeholder="Cari nama peserta atau email...">
    </form>
</div>

            <!-- Tabel Modern -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                        <tr>
                            <th class="px-6 py-4">Peserta</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Bergabung</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($interns as $intern)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold shrink-0">
                                        {{ substr($intern->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $intern->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $intern->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $intern->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('mentor.interns.edit', $intern->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    
                                    <!-- Tombol Hapus (Dengan Modal Konfirmasi AlpineJS) -->
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>

                                        <!-- Modal Backdrop -->
                                        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                                            <div class="bg-white rounded-xl shadow-xl max-w-sm w-full p-6 text-center">
                                                <div class="mx-auto w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                </div>
                                                <h3 class="text-lg font-bold text-gray-900">Hapus Peserta?</h3>
                                                <p class="text-sm text-gray-500 mt-2 mb-6">Apakah Anda yakin ingin menghapus <strong>{{ $intern->name }}</strong>? Data laporan juga akan terhapus.</p>
                                                <div class="flex justify-center gap-3">
                                                    <button @click="open = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                                                    <form action="{{ route('mentor.interns.destroy', $intern->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Ya, Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400">
                                Belum ada data peserta magang.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $interns->links() }} 
            </div>
        </div>
    </div>
</x-admin-layout>