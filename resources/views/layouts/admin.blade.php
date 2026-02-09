<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal - MagangHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex h-full">
        <!-- SIDEBAR (FIXED) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col h-full shadow-2xl">
            
            <div class="flex items-center justify-center h-24 border-b border-white/5 bg-slate-900 shrink-0">
                <h1 class="text-2xl font-black tracking-tighter italic">Aktifitas<span class="text-indigo-400">Peserta.</span></h1>
            </div>

            <nav class="flex-1 px-6 space-y-2 mt-8 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke-width="2.5"></path></svg>
                    <span class="font-bold text-sm">Laporan Masuk</span>
                </a>

                <!-- Cari bagian ini di admin.blade.php dan pastikan namanya mentor.history -->
                <a href="{{ route('mentor.history') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('mentor.history') ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2.5"></path>
                    </svg>
                    <span class="font-bold text-sm">Laporan</span>
                </a>

                <a href="{{ route('mentor.interns.index') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('mentor.interns.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-width="2"></path></svg>
                    <span class="font-bold text-sm">Data Peserta</span>
                </a>

                <a href="{{ route('mentor.performance') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('mentor.performance') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-width="2.5"></path></svg>
                    <span class="font-bold text-sm">Indikator Kinerja</span>
                </a>
            </nav>
            
            <div class="shrink-0 p-6 border-t border-white/5 bg-slate-900">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center font-black text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold truncate">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-[10px] font-black uppercase text-slate-500 hover:text-white transition">Sign Out</button></form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- AREA KONTEN (SCROLLABLE) -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative bg-[#F8FAFC]">
            <!-- Header Mobile -->
            <div class="lg:hidden flex justify-between items-center bg-white border-b p-6 shrink-0 shadow-sm">
                <div class="font-black text-xl italic text-indigo-900">MagangHub.</div>
                <button @click="sidebarOpen = !sidebarOpen" class="text-slate-600 bg-slate-100 p-2.5 rounded-xl"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16" stroke-width="2.5"></path></svg></button>
            </div>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-12 custom-scroll">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>