<x-admin-layout>
    <div class="space-y-8" x-data="{ openModal: false, editMode: false, currentMentor: {id: '', name: '', email: ''} }">

        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">MANAJEMEN INSTRUKTUR</h2>
                <p class="text-sm text-slate-500 font-medium">Kelola hak akses dan akun pembimbing magang.</p>
            </div>
            <button @click="openModal = true; editMode = false; currentMentor = {id:'', name:'', email:''}"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition shadow-xl shadow-indigo-200">
                + Tambah Instruktur
            </button>
        </div>

        <!-- Area Pencarian + Tabel Instruktur -->
        <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
            <!-- Search Bar -->
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 bg-slate-50/60">
                <form method="GET" action="{{ route('super.mentors.index') }}" class="max-w-md">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="block w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm"
                               placeholder="Cari nama instruktur atau email...">
                        @if(request('search'))
                            <a href="{{ route('super.mentors.index') }}" class="absolute inset-y-0 right-3 flex items-center text-[10px] font-black uppercase tracking-widest text-slate-300 hover:text-rose-500">Reset</a>
                        @endif
                    </div>
                </form>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-4">Instruktur</th>
                        <th class="px-6 py-4 text-center">Peserta Binaan</th>
                        <th class="px-6 py-4 text-center">Bergabung</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-sm text-slate-700">
                    @foreach($mentors as $mentor)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-500 uppercase">{{ substr($mentor->name, 0, 1) }}</div>
                                <div>
                                    <p class="text-slate-900 uppercase tracking-tight font-black">{{ $mentor->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold tracking-wide mt-0.5">{{ $mentor->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <a href="{{ route('super.mentors.interns', $mentor->id) }}" class="inline-block px-4 py-1.5 rounded-xl bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100 hover:bg-indigo-100 hover:text-indigo-700 transition">
                                {{ $mentor->interns_count }} Peserta
                            </a>
                        </td>
                        <td class="px-6 py-5 text-center text-sm text-slate-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($mentor->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <div class="flex justify-center gap-4">
                                <button @click="openModal = true; editMode = true; currentMentor = {id: '{{ $mentor->id }}', name: '{{ $mentor->name }}', email: '{{ $mentor->email }}'}"
                                        class="inline-flex items-center gap-1.5 text-indigo-600 hover:underline uppercase text-[10px] font-black tracking-widest">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    <span>Ubah</span>
                                </button>
                                <form action="{{ route('super.mentors.destroy', $mentor->id) }}" method="POST" onsubmit="return confirm('Hapus mentor ini?')">
                                    @csrf @method('DELETE')
                                    <button class="inline-flex items-center gap-1.5 text-rose-500 hover:underline uppercase text-[10px] font-black tracking-widest">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $mentors->links() }}
            </div>
        </div>

        <!-- MODAL TAMBAH/EDIT MENTOR -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md transition-all">
            <div @click.away="openModal = false" class="bg-white rounded-[3rem] p-12 max-w-md w-full shadow-2xl border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>

                <h3 class="text-2xl font-black mb-2 uppercase tracking-tighter" x-text="editMode ? 'Update Instruktur' : 'Tambah Instruktur'"></h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-8">Informasi Akun Pembimbing</p>

                <form action="{{ route('super.mentors.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="id" x-model="currentMentor.id">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="currentMentor.name" required class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Alamat Email</label>
                        <input type="email" name="email" x-model="currentMentor.email" required class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Password <span x-show="editMode" class="text-indigo-400 italic">(Kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" class="w-full rounded-2xl border-slate-100 bg-slate-50 p-4 font-bold text-sm focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all shadow-inner">
                    </div>

                    <div class="pt-4 flex flex-col gap-3">
                        <button class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black uppercase text-[11px] tracking-[0.2em] shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-95">Simpan Data Instruktur</button>
                        <button type="button" @click="openModal = false" class="w-full py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
