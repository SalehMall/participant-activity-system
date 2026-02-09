<x-admin-layout>
    <div class="max-w-6xl mx-auto h-full flex flex-col animate-fade-in">
        
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.interns.index') }}" class="p-2 bg-white rounded-xl border border-slate-200 text-slate-400 hover:text-indigo-600 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="3"></path></svg>
                </a>
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-tight">Update: {{ explode(' ', $user->name)[0] }}</h2>
            </div>
            <span class="text-[10px] font-black bg-amber-50 text-amber-600 px-3 py-1 rounded-lg border border-amber-100 uppercase">Edit Mode</span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 flex-1 overflow-hidden">
            <form method="POST" action="{{ route('mentor.interns.update', $user->id) }}" class="p-6 h-full flex flex-col justify-between">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    <!-- BARIS 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] ml-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>

                    <!-- BARIS 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-cyan-600 uppercase tracking-[0.2em] ml-1">Lokasi</label>
                            <input type="text" name="intern_location" value="{{ old('intern_location', $user->intern_location) }}"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-cyan-600 uppercase tracking-[0.2em] ml-1">Tgl Mulai</label>
                            <input type="date" name="intern_start_date" value="{{ old('intern_start_date', $user->intern_start_date) }}"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner uppercase">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-cyan-600 uppercase tracking-[0.2em] ml-1">Tgl Selesai</label>
                            <input type="date" name="intern_end_date" value="{{ old('intern_end_date', $user->intern_end_date) }}"
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner uppercase">
                        </div>
                    </div>

                    <!-- BARIS 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5 text-slate-400">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] ml-1">Password Baru (Opsional)</label>
                            <input type="password" name="password" 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner" placeholder="Biarkan kosong jika tidak diubah">
                        </div>
                        <div class="space-y-1.5 text-slate-400">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] ml-1">Ulangi Password</label>
                            <input type="password" name="password_confirmation" 
                                class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                        </div>
                    </div>
                </div>

                <div class="pt-8 flex items-center justify-end gap-4 border-t border-slate-50 mt-4">
                    <a href="{{ route('mentor.interns.index') }}" class="text-xs font-black uppercase text-slate-400 hover:text-slate-600 transition-all">Batal</a>
                    <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 shadow-xl shadow-indigo-200 transition transform active:scale-95">
                        Update Data Peserta
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>