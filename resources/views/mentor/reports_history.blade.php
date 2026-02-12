<x-admin-layout>
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in pb-20 px-4 sm:px-6">
        
        <!-- ================= SECTION 1: HEADER ================= -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight" :class="darkMode ? 'text-white' : 'text-slate-900'">
                    Riwayat Laporan
                </h2>
                <p class="text-sm mt-1" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                    Arsip data aktivitas peserta magang yang telah diverifikasi.
                </p>
            </div>
            
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20 gap-2 group">
                <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18" stroke-width="2"></path></svg>
                <span>Laporan Baru</span>
                <span class="bg-indigo-500 text-white text-xs py-0.5 px-2 rounded-md ml-1">{{ $pendingReports->count() }}</span>
            </a>
        </div>

        <!-- ================= SECTION 2: TABEL DATA ================= -->
        <div class="rounded-2xl shadow-sm border overflow-hidden transition-all duration-300 relative"
             :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'">
            
            <!-- Area Scrollable -->
            <div class="overflow-x-auto relative" style="max-height: 700px; overflow-y: auto;">
                <table class="w-full border-collapse text-left">
                    <!-- HEADER TABEL -->
                    <thead class="sticky top-0 z-20 shadow-sm" :class="darkMode ? 'bg-slate-900' : 'bg-slate-50'">
                        <tr class="text-xs font-semibold uppercase tracking-wider" :class="darkMode ? 'text-slate-400' : 'text-slate-500'">
                            <th class="px-6 py-4 w-1/4">Waktu & Peserta</th>
                            <th class="px-6 py-4 w-1/3">Aktivitas</th>
                            <th class="px-6 py-4 text-center">Bukti Foto</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <!-- BODY TABEL -->
                    <tbody class="divide-y" :class="darkMode ? 'divide-slate-700' : 'divide-slate-100'">
                        @foreach($historyReports as $report)
                        <tr x-data="{ openUpdate: false, openDelete: false }" 
                            class="transition-colors group"
                            :class="darkMode ? 'hover:bg-slate-700/50' : 'hover:bg-slate-50'">
                            
                            <!-- Peserta -->
                            <td class="px-6 py-4 align-top">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm" :class="darkMode ? 'text-white' : 'text-slate-900'">
                                        {{ $report->user->name }}
                                    </span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="text-xs font-medium text-slate-500">
                                            {{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Aktivitas -->
                            <td class="px-6 py-4 align-top">
                                <div class="max-w-md">
                                    <p class="text-sm leading-relaxed" :class="darkMode ? 'text-slate-300' : 'text-slate-700'">
                                        {{ $report->aktivitas }}
                                    </p>
                                    @if($report->komentar_mentor)
                                        <div class="mt-2 flex items-start gap-2 p-2 rounded-lg border" :class="darkMode ? 'bg-indigo-900/20 border-indigo-500/30' : 'bg-indigo-50 border-indigo-100'">
                                            <svg class="w-4 h-4 text-indigo-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                                            <div class="text-xs">
                                                <span class="font-bold text-indigo-500 block mb-0.5">Feedback Instruktur:</span>
                                                <span class="italic" :class="darkMode ? 'text-indigo-200' : 'text-indigo-700'">"{{ $report->komentar_mentor }}"</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Bukti Foto -->
                            <td class="px-6 py-4 align-top text-center">
                                @if($report->gambar)
                                    <a href="{{ asset('storage/' . $report->gambar) }}" target="_blank" class="inline-block group/img relative">
                                        <img src="{{ asset('storage/' . $report->gambar) }}" 
                                             class="w-14 h-14 rounded-lg object-cover shadow-sm border border-slate-200 transition-transform duration-300 group-hover/img:scale-105">
                                        <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/10 transition-colors rounded-lg"></div>
                                    </a>
                                @else 
                                    <div class="w-14 h-14 mx-auto rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200" :class="darkMode ? 'bg-slate-700 border-slate-600' : 'bg-slate-100'">
                                        <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 align-top text-center">
                                @php
                                    $statusConfig = match($report->status) {
                                        'approved' => ['label' => 'Diterima', 'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-500/20 dark:text-emerald-400 dark:border-emerald-500/30'],
                                        'revision' => ['label' => 'Revisi', 'class' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/20 dark:text-amber-400 dark:border-amber-500/30'],
                                        'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-500/20 dark:text-rose-400 dark:border-rose-500/30'],
                                        'permit'   => ['label' => 'Izin', 'class' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-500/20 dark:text-blue-400 dark:border-blue-500/30'],
                                        'alpha'    => ['label' => 'Alpha', 'class' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700 dark:text-slate-300'],
                                        default    => ['label' => 'Pending', 'class' => 'bg-slate-100 text-slate-500 border-slate-200']
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusConfig['class'] }}">
                                    {{ $statusConfig['label'] }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 align-top text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Tombol Ubah -->
                                    <button @click="openUpdate = true" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30 transition-colors" title="Ubah Status">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    <!-- Tombol Hapus -->
                                    <button @click="openDelete = true" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>

                                <!-- MODAL UPDATE STATUS -->
                                <div x-show="openUpdate" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="openUpdate = false"></div>
                                    
                                    <div x-show="openUpdate" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
                                         class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6 border border-slate-200 dark:border-slate-700 text-left">
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Update Status</h3>
                                            <button @click="openUpdate = false" class="text-slate-400 hover:text-slate-500"><svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></button>
                                        </div>
                                        
                                        <form action="{{ route('reports.update', $report->id) }}" method="POST" class="space-y-3">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="komentar_mentor" value="{{ $report->komentar_mentor }}">
                                            
                                            <button name="status" value="approved" class="w-full flex items-center justify-between px-4 py-3 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 rounded-xl transition-colors font-medium text-sm group">
                                                <span>Terima Laporan</span>
                                                <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                            
                                            <button name="status" value="revision" class="w-full flex items-center justify-between px-4 py-3 bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 rounded-xl transition-colors font-medium text-sm group">
                                                <span>Minta Revisi</span>
                                                <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            
                                            <button name="status" value="rejected" class="w-full flex items-center justify-between px-4 py-3 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-xl transition-colors font-medium text-sm group">
                                                <span>Tolak Laporan</span>
                                            </button>

                                            <button name="status" value="alpha" class="w-full flex items-center justify-between px-4 py-3 bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200 rounded-xl transition-colors font-medium text-sm group">
                                                <span>Tandai Alpha (Tidak Hadir)</span>
                                                <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- MODAL DELETE -->
                                <div x-show="openDelete" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openDelete = false"></div>
                                    <div x-show="openDelete" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                         class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6 text-center border border-slate-200 dark:border-slate-700">
                                        
                                        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </div>
                                        
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Hapus Laporan?</h3>
                                        <p class="text-sm text-slate-500 mb-6">Data yang dihapus tidak dapat dikembalikan lagi.</p>
                                        
                                        <div class="flex gap-3">
                                            <button @click="openDelete = false" class="flex-1 py-2.5 px-4 bg-white border border-slate-300 rounded-xl text-slate-700 font-medium hover:bg-slate-50 transition-colors text-sm">Batal</button>
                                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="flex-1">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full py-2.5 px-4 bg-rose-600 text-white rounded-xl font-medium hover:bg-rose-700 transition-colors shadow-lg shadow-rose-500/30 text-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="px-6 py-4 border-t" :class="darkMode ? 'bg-slate-800 border-slate-700' : 'bg-white border-slate-200'">
                {{ $historyReports->links() }}
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-widest">End of Archive</p>
        </div>
    </div>
</x-admin-layout>
