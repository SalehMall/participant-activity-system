<x-admin-layout>
    <div class="space-y-8">
        @php
            $today = \Carbon\Carbon::now()->translatedFormat('d F Y');
            $totalReports = array_sum($stats);
            $pApproved = $totalReports ? round($stats['approved'] / $totalReports * 100) : 0;
            $pRevision = $totalReports ? round($stats['revision'] / $totalReports * 100) : 0;
            $pRejected = $totalReports ? round($stats['rejected'] / $totalReports * 100) : 0;
            $pPermit   = $totalReports ? round($stats['permit']   / $totalReports * 100) : 0;
            $pAlpha    = $totalReports ? round($stats['alpha']    / $totalReports * 100) : 0;
            $pPending  = $totalReports ? round($stats['pending']  / $totalReports * 100) : 0;
        @endphp

        <div class="bg-white rounded-3xl border border-slate-200 p-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black tracking-tight">Dashboard Instruktur</h2>
                    <p class="text-sm text-slate-500">Tanggal {{ $today }}</p>
                </div>
                <div class="flex gap-3 items-center">
                    <a href="{{ route('mentor.history') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-indigo-700">Riwayat</a>
                    <a href="{{ route('mentor.interns.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-200">Peserta</a>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-50 rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Peserta Binaan</p>
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM15 21H3v-1a6 6 0 0112 0v1"></path></svg>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold">{{ $internCount }}</span>
                    <span class="text-xs opacity-50">orang</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Pending</p>
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path></svg>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold">{{ $stats['pending'] }}</span>
                    <span class="text-xs opacity-50">laporan</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Diterima</p>
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold">{{ $stats['approved'] }}</span>
                    <span class="text-xs opacity-50">laporan</span>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl border border-slate-200 p-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Revisi</p>
                    <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"></path></svg>
                </div>
                <div class="mt-3 flex items-end gap-2">
                    <span class="text-3xl font-extrabold">{{ $stats['revision'] }}</span>
                    <span class="text-xs opacity-50">laporan</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold">Grafik Status Laporan</h3>
                </div>
                <div class="px-8 py-6">
                    <div id="chartStatusMentor" style="height: 320px"></div>
                </div>
            </div>
            <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold">Pending Terkini</h3>
                    <a href="{{ route('mentor.history') }}" class="text-[10px] font-black uppercase tracking-widest text-indigo-600">Lihat</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($pendingReports as $report)
                        <div class="px-8 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-yellow-400/20 text-yellow-700 flex items-center justify-center text-xs font-black">!</div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-800">{{ $report->user?->name ?? 'Peserta' }}</p>
                                <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($report->tanggal)->translatedFormat('d F Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-widest">Pending</span>
                        </div>
                    @empty
                        <div class="px-8 py-12 text-center text-slate-400 font-black uppercase tracking-widest text-xs italic">Tidak ada pending.</div>
                    @endforelse
                </div>
            </div>
        </div>




    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        (function() {
            var seriesData = [{{ $stats['approved'] ?? 0 }}, {{ $stats['revision'] ?? 0 }}, {{ $stats['rejected'] ?? 0 }}, {{ $stats['permit'] ?? 0 }}, {{ $stats['alpha'] ?? 0 }}, {{ $stats['pending'] ?? 0 }}];
            var labels = ['Diterima','Revisi','Ditolak','Izin','Alpha','Pending'];
            var options = {
                chart: { type: 'bar', height: 320, toolbar: { show: false } },
                series: [{ name: 'Laporan', data: seriesData }],
                xaxis: { categories: labels },
                colors: ['#22c55e','#f59e0b','#ef4444','#3b82f6','#be123c','#94a3b8'],
                dataLabels: { enabled: true, style: { fontSize: '12px' } },
                plotOptions: { bar: { borderRadius: 6, columnWidth: '45%', dataLabels: { position: 'top' }, distributed: true } },
                grid: { strokeDashArray: 4 },
                legend: { show: false }
            };
            var el = document.querySelector('#chartStatusMentor');
            if (el) { new ApexCharts(el, options).render(); }
        })();
    </script>
</x-admin-layout>
