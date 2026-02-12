<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - DailyLog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex h-full">
        <!-- SIDEBAR FIXED -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0F172A] text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col h-full shadow-2xl">

            <!-- Logo -->
            <div class="flex items-center px-8 h-24 border-b border-white/5 shrink-0">
                <h1 class="text-2xl font-extrabold tracking-tighter">Daily<span class="text-indigo-400">Log.</span></h1>
            </div>

            <!-- Menu Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">

                <!-- DASHBOARD UMUM (Mentor & Super Admin) -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-indigo-600 shadow-lg shadow-indigo-500/40 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-bold text-sm uppercase tracking-wider">
                        {{ Auth::user()->role === 'super_admin' ? 'Dashboard' : 'Laporan Masuk' }}
                    </span>
                </a>

                <!-- MENU KHUSUS SUPER ADMIN -->
                @if(Auth::user()->role === 'super_admin')
                    <div class="pt-6 pb-2 px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Management Area</div>
                    <a href="{{ route('super.mentors.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('super.mentors.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Kelola Instruktur</span>
                    </a>
                    <a href="{{ route('super.interns.all') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('super.interns.all') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Seluruh Peserta</span>
                    </a>
                @endif

                <!-- MENU KHUSUS MENTOR -->
                @if(Auth::user()->role === 'mentor')
                    <div class="pt-6 pb-2 px-4 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em]">Instruktur Area</div>
                    <a href="{{ route('mentor.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('mentor.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h10M4 18h6"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Dashboard</span>
                    </a>
                    <a href="{{ route('mentor.history') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('mentor.history*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Riwayat Laporan</span>
                    </a>
                    <a href="{{ route('mentor.interns.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('mentor.interns.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Data Peserta</span>
                    </a>
                    <a href="{{ route('mentor.performance') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('mentor.performance') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/40' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="font-bold text-sm uppercase tracking-wider">Indikator Kinerja</span>
                    </a>
                @endif
            </nav>

            <!-- User Profile (Fixed Bottom) -->
            <div class="shrink-0 p-6 border-t border-white/5">
                <div class="flex items-center gap-3 bg-white/5 p-3 rounded-2xl cursor-pointer" @click="window.location.href='{{ route('profile.edit') }}'">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center font-black text-white uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                    <div class="overflow-hidden flex-1">
                        <p class="text-xs font-black text-white uppercase truncate">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-[10px] font-bold text-slate-400 hover:text-rose-400 uppercase tracking-widest transition-colors" @click.stop>Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN AREA -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Mobile Header -->
            <header class="lg:hidden flex items-center justify-between px-8 h-20 bg-white border-b border-slate-200 shrink-0">
                <h1 class="text-xl font-black tracking-tighter">Daily<span class="text-indigo-600">Log.</span></h1>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-slate-50 rounded-xl text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-12">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
