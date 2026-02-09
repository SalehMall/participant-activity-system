<x-admin-layout>
    <!-- Wrapper Utama: h-full agar mengikuti tinggi layar sidebar -->
    <div x-data="{ viewMode: localStorage.getItem('historyView') || 'grid' }" 
         x-init="$watch('viewMode', val => localStorage.setItem('historyView', val))"
         class="flex flex-col h-[calc(100vh-80px)] space-y-6 animate-fade-in">
        
        <!-- ================= SECTION 1: HEADER & SEARCH (FIXED) ================= -->
        <div class="shrink-0 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Laporan</h2>
                    <p class="text-slate-500 text-sm font-medium">Pilih peserta untuk melihat detail aktivitas.</p>
                </div>
                
                <!-- TOGGLE VIEW -->
                <div class="bg-slate-200/60 p-1 rounded-2xl inline-flex border border-slate-200 shadow-inner">
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Grid
                    </button>
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        List
                    </button>
                </div>
            </div>

            <!-- SEARCH BAR MODERN -->
            <form method="GET" action="{{ route('mentor.history') }}" class="relative max-w-lg group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-[1.5rem] text-sm font-bold text-slate-700 placeholder-slate-400 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-sm" 
                    placeholder="Cari nama peserta magang atau email...">
                @if(request('search'))
                    <a href="{{ route('mentor.history') }}" class="absolute inset-y-0 right-4 flex items-center text-slate-300 hover:text-rose-500 uppercase text-[9px] font-black tracking-widest">Reset</a>
                @endif
            </form>
        </div>

        <!-- ================= SECTION 2: AREA DATA (SCROLLABLE) ================= -->
        <!-- flex-1 agar mengambil sisa layar, overflow-y-auto agar hanya ini yang scroll -->
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            
            <!-- GRID MODE -->
            <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($interns as $intern)
                <a href="{{ route('mentor.history.user', $intern->id) }}" class="group">
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 text-center transition-all duration-500 hover:shadow-2xl hover:border-indigo-500 relative overflow-hidden h-full flex flex-col justify-between">
                        <div class="relative z-10">
                            <div class="w-20 h-20 rounded-[2rem] bg-slate-900 text-white flex items-center justify-center text-2xl font-black mx-auto mb-4 group-hover:rotate-6 transition-transform shadow-lg">
                                {{ substr($intern->name, 0, 1) }}
                            </div>
                            <h4 class="font-black text-slate-800 uppercase text-xs tracking-tight line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $intern->name }}</h4>
                            <p class="text-[9px] font-bold text-slate-400 uppercase mt-1 truncate tracking-widest">{{ $intern->email }}</p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-50 flex justify-between items-center text-[10px] font-black uppercase">
                            <span class="text-slate-300 tracking-tighter">Arsip:</span>
                            <span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">{{ $intern->reports_count }} Laporan</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-20 opacity-20 font-black uppercase tracking-widest italic text-sm">Tidak ada hasil pencarian.</div>
                @endforelse
            </div>

            <!-- LIST MODE -->
            <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" style="display: none;">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 z-20">
                            <tr class="bg-slate-900 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                                <th class="px-10 py-6 text-white">Peserta</th>
                                <th class="px-6 py-6">Email</th>
                                <th class="px-6 py-6 text-center">Total Arsip</th>
                                <th class="px-10 py-6 text-right">Manajemen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($interns as $intern)
                            <tr class="hover:bg-indigo-50/40 transition-colors group">
                                <td class="px-10 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-xs font-black text-slate-500 uppercase">{{ substr($intern->name, 0, 1) }}</div>
                                        <span class="font-black text-slate-800 text-sm uppercase tracking-tight">{{ $intern->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-sm font-bold text-slate-400 tracking-tight">{{ $intern->email }}</td>
                                <td class="px-6 py-5 text-center text-sm font-black text-indigo-600"><span class="bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100">{{ $intern->reports_count }}</span></td>
                                <td class="px-10 py-5 text-right">
                                    <a href="{{ route('mentor.history.user', $intern->id) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-indigo-600 transition-all shadow-md active:scale-95">Detail <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================= SECTION 3: PAGINATION (FIXED BOTTOM) ================= -->
        <div class="shrink-0 px-8 py-5 bg-white rounded-[2rem] border border-slate-200 shadow-sm">
            {{ $interns->links() }}
        </div>
    </div>

    <!-- CSS UNTUK CUSTOM SCROLLBAR AREA DATA -->
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</x-admin-layout>