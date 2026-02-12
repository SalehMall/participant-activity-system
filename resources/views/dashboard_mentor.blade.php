<x-admin-layout>
    <!-- Container dibatasi lebarnya agar fokus di tengah dan tidak melebar kemana-mana -->
    <div class="max-w-5xl mx-auto space-y-5 animate-fade-in">
        
        <!-- Header: Tipis dan Minimalis -->
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Masuk</h2>
                <p class="text-xs text-slate-500 font-medium italic">Menunggu konfirmasi Anda</p>
            </div>
            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="text-[11px] font-bold text-slate-700 uppercase tracking-tighter">{{ $pendingReports->count() }} Laporan Baru</span>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="p-3 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-[11px] border border-emerald-100 flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button @click="show = false">✕</button>
            </div>
        @endif

        <!-- Daftar Laporan (Extra Compact) -->
        <div class="space-y-4">
            @forelse($pendingReports as $report)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-400 transition-all duration-300 group">
                
                <div class="p-5">
                    <!-- Baris Atas: Profil Singkat -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-bold text-sm shadow-sm transition-transform group-hover:scale-105">
                                {{ substr($report->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-tight">{{ $report->user->name }}</h4>
                                <p class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-md text-[9px] font-black uppercase bg-amber-50 text-amber-600 border border-amber-100 shadow-sm">Pending</span>
                    </div>

                    <!-- Layout Utama: Aktivitas & Gambar Berjejer -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                        
                        <!-- Box Teks: text-sm agar muat banyak -->
                        <div class="md:col-span-3 bg-slate-50/50 rounded-xl p-4 border border-slate-100 relative shadow-inner">
                            <p class="text-[9px] uppercase font-black text-indigo-500 mb-2 tracking-widest opacity-70">Laporan Aktivitas:</p>
                            <p class="text-sm text-slate-700 leading-relaxed font-medium italic">"{{ $report->aktivitas }}"</p>
                        </div>
                        
                        <!-- Box Gambar: Mini Preview -->
                        <div class="md:col-span-1">
                            @if($report->gambar)
                                <div class="relative group/img overflow-hidden rounded-xl border-2 border-white shadow-md h-24 w-full">
                                    <img src="{{ asset('storage/' . $report->gambar) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/img:scale-110">
                                    <a href="{{ asset('storage/' . $report->gambar) }}" target="_blank" class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-[9px] text-white font-bold uppercase tracking-tighter">Buka Foto</span>
                                    </a>
                                </div>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-200 h-24 flex items-center justify-center text-slate-300 text-[9px] font-bold uppercase tracking-widest bg-slate-50/30">
                                    No Foto
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Footer: Form Action Ringkas -->
                    <div class="mt-4 pt-4 border-t border-slate-50">
                        <form action="{{ route('reports.update', $report->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                            @csrf @method('PATCH')
                            <div class="flex-1">
                                <input type="text" name="komentar_mentor" 
                                    class="w-full px-4 py-2 rounded-lg border-slate-200 bg-slate-50 text-[11px] font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder-slate-400 shadow-inner" 
                                    placeholder="TULIS CATATAN DISINI...">
                            </div>
                            <div class="flex gap-2">
                                <button name="status" value="approved" class="flex-1 sm:flex-none px-5 py-2 bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-all shadow-sm">Setujui</button>
                                <button name="status" value="revision" class="flex-1 sm:flex-none px-5 py-2 bg-amber-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-amber-600 transition-all shadow-sm">Revisi</button>
                                <button name="status" value="rejected" class="flex-1 sm:flex-none px-5 py-2 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-rose-700 transition-all shadow-sm">Tolak</button>
                                <button name="status" value="alpha" class="flex-1 sm:flex-none px-5 py-2 bg-slate-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-slate-800 transition-all shadow-sm">Alpha</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center opacity-50">
                <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7" stroke-width="3"></path></svg>
                <p class="text-[10px] font-black uppercase tracking-[0.3em]">No Pending Data</p>
            </div>
            @endforelse
        </div>
    </div>
</x-admin-layout>
