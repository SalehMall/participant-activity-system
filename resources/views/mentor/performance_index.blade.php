<x-admin-layout>
    <div class="max-w-7xl mx-auto">
        
        <!-- Header & Breadcrumb -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Indikator Kinerja</h2>
                <div class="text-sm breadcrumbs text-gray-500 mt-2 flex gap-2 items-center">
                    <span class="font-medium text-gray-700">Dashboard</span>
                    <span class="text-gray-300">/</span>
                    <span class="text-indigo-600">Manajemen Target</span>
                </div>
            </div>
            
            <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>{{ $interns->count() }} Peserta Aktif</span>
            </div>
        </div>

        <!-- Alert Sukses -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-xl flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600">&times;</button>
            </div>
        @endif

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($interns as $intern)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 flex flex-col h-full overflow-hidden group">
                
                <!-- Card Header -->
                <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-indigo-200 shrink-0">
                        {{ substr($intern->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="font-bold text-gray-900 truncate" title="{{ $intern->name }}">{{ $intern->name }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $intern->email }}</p>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6 flex-1 flex flex-col">
                    <form action="{{ route('mentor.performance.update', $intern->id) }}" method="POST" class="flex-1 flex flex-col h-full">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex-1 mb-4">
                            <label class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Target / Capaian
                            </label>
                            
                            <!-- PERBAIKAN TEXTAREA DISINI -->
                            <textarea name="performance_target" rows="6" 
                                class="w-full block p-4 rounded-xl border-gray-200 bg-gray-50 text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all resize-none leading-relaxed" 
                                placeholder="Tuliskan target kinerja untuk peserta ini...&#10;&#10;Contoh:&#10;1. Menyelesaikan Modul A&#10;2. Membantu Administrasi">{{ $intern->performance_target }}</textarea>
                            
                            <p class="text-xs text-gray-400 mt-2 italic">* Tekan Enter untuk baris baru</p>
                        </div>
                        
                        <div class="pt-2">
                            <button type="submit" class="w-full group-hover:bg-indigo-600 group-hover:text-white bg-white border border-indigo-200 text-indigo-700 font-bold py-2.5 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum Ada Peserta</h3>
                <p class="text-gray-500 mt-1 mb-6">Tambahkan peserta magang terlebih dahulu.</p>
                <a href="{{ route('mentor.interns.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
                    Tambah Peserta
                </a>
            </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>