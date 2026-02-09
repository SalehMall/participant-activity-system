<x-admin-layout>
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in pb-20">
        
        <!-- HEADER DENGAN TOMBOL KEMBALI -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('mentor.history') }}" class="p-3 bg-white rounded-2xl border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3"></path></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Arsip: {{ $user->name }}</h2>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">Monitoring data aktivitas individu</p>
                </div>
            </div>
            
            <div class="bg-indigo-50 px-5 py-2 rounded-2xl border border-indigo-100 shadow-sm">
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Total Record</p>
                <p class="text-lg font-black text-indigo-700 leading-none">{{ $historyReports->total() }} Data</p>
            </div>
        </div>

        <!-- TABEL DATA (CLEAN & PREMIUM) -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden relative">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">
                            <th class="px-8 py-6 text-white text-left">Tanggal</th>
                            <th class="px-6 py-6 text-left w-1/3">Aktivitas</th>
                            <th class="px-6 py-6 text-center">Bukti Foto</th>
                            <th class="px-6 py-6 text-center">Status</th>
                            <th class="px-8 py-6 text-right">Opsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-slate-700">
                        @foreach($historyReports as $report)
                        <tr class="hover:bg-indigo-50/30 transition-colors group" x-data="{ openUpdate: false, openDelete: false }">
                            
                            <td class="px-8 py-6 whitespace-nowrap">
                                <span class="font-black text-xs uppercase tracking-tighter text-slate-800">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d M Y') }}</span>
                                <p class="text-[9px] font-bold text-slate-400 mt-1 uppercase">{{ \Carbon\Carbon::parse($report->tanggal)->format('l') }}</p>
                            </td>

                            <td class="px-6 py-6 text-slate-600">
                                <p class="text-sm font-medium line-clamp-1 group-hover:line-clamp-none transition-all duration-500 italic">"{{ $report->aktivitas }}"</p>
                                @if($report->komentar_mentor)
                                    <p class="mt-2 text-[10px] text-indigo-500 font-bold uppercase italic">F: {{ $report->komentar_mentor }}</p>
                                @endif
                            </td>

                            <td class="px-6 py-6 text-center">
                                @if($report->gambar)
                                    <a href="{{ asset('storage/' . $report->gambar) }}" target="_blank" class="inline-block relative group/img">
                                        <img src="{{ asset('storage/' . $report->gambar) }}" class="w-16 h-16 rounded-2xl object-cover border-4 border-white shadow-md hover:scale-110 transition-all">
                                    </a>
                                @else - @endif
                            </td>

                            <td class="px-6 py-6 text-center">
                                @php
                                    $label = match($report->status) { 'approved' => 'DITERIMA', 'revision' => 'REVISI', 'rejected' => 'DITOLAK', 'permit' => 'IZIN', 'alpha' => 'ALPHA', default => 'PENDING' };
                                    $color = match($report->status) { 'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'revision' => 'bg-amber-50 text-amber-600 border-amber-100', 'rejected' => 'bg-rose-50 text-rose-600 border-rose-100', 'permit' => 'bg-blue-50 text-blue-600 border-blue-100', 'alpha' => 'bg-slate-900 text-white', default => 'bg-slate-50' };
                                @endphp
                                <span class="inline-flex px-4 py-1.5 rounded-full text-[9px] font-black tracking-widest border {{ $color }} uppercase">{{ $label }}</span>
                            </td>

                            <td class="px-8 py-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <button @click="openUpdate = true" class="px-4 py-2 bg-slate-50 hover:bg-indigo-600 hover:text-white border border-slate-100 text-indigo-600 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm">Ubah</button>
                                    <button @click="openDelete = true" class="p-2 text-slate-300 hover:text-rose-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2.2"></path></svg>
                                    </button>

                                    <!-- MODAL UPDATE (SAME LOGIC) -->
                                    <div x-show="openUpdate" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openUpdate = false"></div>
                                        <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl border border-slate-100 text-center">
                                            <h3 class="text-xl font-black uppercase mb-6 tracking-tighter">Ubah Status</h3>
                                            <form action="{{ route('reports.update', $report->id) }}" method="POST" class="flex flex-col gap-3">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="komentar_mentor" value="{{ $report->komentar_mentor }}">
                                                <button name="status" value="approved" class="w-full py-4 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-2xl text-[11px] font-black uppercase transition-all">✓ Terima</button>
                                                <button name="status" value="revision" class="w-full py-4 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-2xl text-[11px] font-black uppercase transition-all">✎ Revisi</button>
                                                <button name="status" value="rejected" class="w-full py-4 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-2xl text-[11px] font-black uppercase transition-all">✕ Tolak</button>
                                                <button type="button" @click="openUpdate = false" class="mt-2 text-slate-400 text-[10px] uppercase font-bold">Batal</button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <!-- MODAL DELETE (SAME LOGIC) -->
                                    <div x-show="openDelete" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                                        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openDelete = false"></div>
                                        <div class="relative bg-white rounded-[2.5rem] p-10 max-w-sm w-full shadow-2xl border border-slate-100 text-center">
                                            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2.5"></path></svg></div>
                                            <h3 class="text-xl font-black mb-8 uppercase">Hapus Laporan?</h3>
                                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full py-4 bg-rose-600 text-white rounded-2xl text-[11px] font-black uppercase shadow-lg shadow-rose-200 mb-2">Ya, Hapus</button>
                                                <button type="button" @click="openDelete = false" class="w-full py-4 bg-slate-50 text-slate-400 rounded-2xl text-[11px] font-black uppercase">Batal</button>
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
            <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 rounded-b-[2rem]">
                {{ $historyReports->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>