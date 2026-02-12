<x-admin-layout>
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in pb-10">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-4">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-slate-900">SELURUH PESERTA MAGANG</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Monitoring data seluruh peserta dari semua mentor pembimbing.</p>
            </div>

            <div class="bg-indigo-600 px-6 py-3 rounded-2xl shadow-lg shadow-indigo-200">
                <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest">Total Peserta</p>
                <p class="text-2xl font-black text-white leading-none">{{ $interns->total() }}</p>
            </div>
        </div>

        <!-- Area Pencarian + Tabel Data -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden transition-all duration-500">

            <!-- Search Bar -->
            <div class="px-6 pt-6 pb-4 border-b border-slate-100 bg-slate-50/60">
                <form method="GET" action="{{ route('super.interns.all') }}" class="max-w-md">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="block w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-700 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm"
                            placeholder="Cari nama peserta atau email..."
                        >
                        @if(request('search'))
                            <a href="{{ route('super.interns.all') }}" class="absolute inset-y-0 right-3 flex items-center text-[10px] font-black uppercase tracking-widest text-slate-300 hover:text-rose-500">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase font-bold text-xs">
                            <th class="px-6 py-4 text-left">Peserta</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Bergabung</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 font-bold text-sm">
                        @forelse($interns as $intern)
                        <tr class="hover:bg-indigo-50/30 transition-colors group">
                            <!-- Nama & Email -->
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-500 uppercase">{{ substr($intern->name, 0, 1) }}</div>
                                    <div>
                                        <p class="text-slate-900 uppercase tracking-tight">{{ $intern->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-medium tracking-wide lowercase">{{ $intern->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-6 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Aktif</span>
                            </td>

                            <!-- Bergabung -->
                            <td class="px-6 py-6 text-center text-sm text-slate-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($intern->created_at)->translatedFormat('d M Y') }}
                            </td>

                            <!-- Aksi (Jika Ingin Tambah Detail) -->
                            <td class="px-8 py-6 text-center">
                                <a href="{{ route('mentor.history.user', $intern->id) }}" class="text-indigo-600 hover:underline text-[10px] font-black uppercase tracking-widest">
                                    Cek Riwayat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center text-slate-300 font-black uppercase tracking-widest text-xs italic">Belum ada data peserta magang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="px-10 py-6 bg-slate-50 border-t border-slate-100">
                {{ $interns->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
