<x-app-layout>
    <!-- CSS: ANIMASI ANGKASA & GLASSMORPHISM -->
    <style>
        /* --- ANIMASI MODE ANGKASA --- */
        @keyframes gradient-move { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
        @keyframes twinkle { 0%, 100% { opacity: 0.3; transform: scale(1); } 50% { opacity: 1; transform: scale(1.3); } }
        @keyframes shooting { 0% { transform: translateX(0) translateY(0) rotate(45deg); opacity: 0; } 10% { opacity: 1; } 30% { width: 150px; } 100% { transform: translateX(1000px) translateY(1000px) rotate(45deg); opacity: 0; } }

        .dark-theme-active {
            background: #020617 !important;
            background-image: linear-gradient(to bottom right, #020617, #0f172a, #1e1b4b) !important;
            background-size: 400% 400%;
            animation: gradient-move 15s ease infinite;
        }

        .star { position: absolute; background: white; border-radius: 50%; pointer-events: none; animation: twinkle var(--duration) infinite ease-in-out; }
        .shooting-star { position: absolute; height: 2px; width: 0; background: linear-gradient(-45deg, #5f91ff, rgba(0,0,255,0)); filter: drop-shadow(0 0 6px #6cb1ff); animation: shooting 4000ms ease-in-out infinite; pointer-events: none; }
        .glass-dark { background: rgba(15, 23, 42, 0.6) !important; backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.15) !important; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4); }
    </style>

    <!-- WRAPPER UTAMA -->
    <div x-data="calendarApp({{ json_encode($calendarData) }})"
         :class="darkMode ? 'dark-theme-active text-white' : 'bg-[#F5F7FA] text-slate-800'"
         class="py-8 min-h-screen font-sans transition-colors duration-700 relative overflow-hidden">
        
        <!-- ELEMENT ANGKASA (Hanya di Mode Gelap) -->
        <template x-if="darkMode">
            <div class="absolute inset-0 pointer-events-none">
                @for ($i = 0; $i < 150; $i++)
                    <div class="star shadow-white shadow-sm" style="width:{{rand(1,3)}}px; height:{{rand(1,3)}}px; top:{{rand(0,100)}}%; left:{{rand(0,100)}}%; --duration:{{rand(2,6)}}s; animation-delay:{{rand(0,5)}}s;"></div>
                @endfor
                <div class="shooting-star" style="top:5%; left:30%;"></div>
                <div class="shooting-star" style="top:40%; left:10%; animation-delay:3s;"></div>
                <div class="shooting-star" style="top:20%; left:70%; animation-delay:5s;"></div>
            </div>
        </template>

        <div class="relative z-10 max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-xl font-bold tracking-tight">Dashboard Peserta Pelatihan</h2>
                
                <button @click="toggleTheme()" 
                        class="p-2 rounded-2xl transition-all duration-500 shadow-lg flex items-center gap-3 font-bold text-[10px] uppercase tracking-widest"
                        :class="darkMode ? 'bg-white/10 text-yellow-400 border border-white/10' : 'bg-white text-indigo-600 border border-slate-200'">
                    <span x-show="!darkMode">🌙 Mode Angkasa</span>
                    <span x-show="darkMode">☀️ Mode Terang</span>
                </button>
            </div>

            <!-- INFO PESERTA -->
            <div :class="darkMode ? 'glass-dark' : 'bg-white border border-gray-100'" class="rounded-xl shadow-sm p-6 sm:p-8 transition-all duration-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm opacity-50 mb-1 font-semibold">Nama Peserta Pelatihan</p>
                            <h3 class="text-lg font-bold uppercase tracking-wide">{{ Auth::user()->name }}</h3>
                        </div>
                        <div>
                            <p class="text-sm opacity-50 mb-1 font-semibold">Posisi</p>
                            <h3 class="text-lg font-bold">Peserta Pelatihan</h3>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm opacity-50 mb-1 font-semibold">Lokasi Pelatihan</p>
                            <h3 class="text-lg font-bold">{{ Auth::user()->intern_location ?? 'MagangHub Office' }}</h3>
                        </div>
                        <div>
                            <p class="text-sm opacity-50 mb-1 font-semibold">Periode Pelatihan</p>
                            <h3 class="text-lg font-bold">
                                @if(Auth::user()->intern_start_date && Auth::user()->intern_end_date)
                                    {{ \Carbon\Carbon::parse(Auth::user()->intern_start_date)->translatedFormat('d F Y') }} - 
                                    {{ \Carbon\Carbon::parse(Auth::user()->intern_end_date)->translatedFormat('d F Y') }}
                                @else
                                    16 Desember 2025 - 16 Juni 2026
                                @endif
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATISTIK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @php
                    $statsConfig = [
                        ['label' => 'Hadir', 'key' => 'hadir', 'color' => 'bg-green-600'],
                        ['label' => 'Perlu Revisi', 'key' => 'revisi', 'color' => 'bg-yellow-400'],
                        ['label' => 'Tidak Hadir (Ket)', 'key' => 'izin', 'color' => 'bg-blue-500'],
                        ['label' => 'Tidak Hadir (Tanpa Ket)', 'key' => 'alpha', 'color' => 'bg-red-600'],
                        ['label' => 'Kehadiran Ditolak', 'key' => 'ditolak', 'color' => 'bg-red-600'],
                    ];
                @endphp
                @foreach($statsConfig as $item)
                <div :class="darkMode ? 'glass-dark border-white/20' : 'bg-white border-gray-100'" class="rounded-xl p-6 shadow-sm border flex flex-col justify-between h-36 transition-all duration-500 hover:scale-[1.02]">
                    <div>
                        <p class="mb-1 text-sm font-medium" :class="darkMode ? 'text-slate-300' : 'text-gray-600'">{{ $item['label'] }}</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-bold">{{ $stats[$item['key']] }}</span>
                            <span class="text-xs opacity-50">laporan</span>
                        </div>
                    </div>
                    <div class="w-full h-1.5 rounded-full mt-2 shadow-sm {{ $item['color'] }}"></div>
                </div>
                @endforeach
            </div>

            <!-- INDIKATOR KINERJA -->
            <div :class="darkMode ? 'glass-dark border-white/20' : 'bg-white border border-gray-100'" class="rounded-xl shadow-sm p-6 transition-all duration-500">
                <h4 class="text-gray-600 font-medium mb-2 text-sm uppercase tracking-wide" :class="darkMode ? 'text-indigo-300' : ''">Indikator Kinerja</h4>
                <div class="text-sm font-bold leading-relaxed whitespace-pre-line" :class="darkMode ? 'text-white' : 'text-gray-900'">
                    @if(Auth::user()->performance_target)
                        {!! nl2br(e(Auth::user()->performance_target)) !!}
                    @else
                        <span class="opacity-40 italic font-normal text-xs tracking-wide tracking-tighter">Belum ada indikator kinerja yang ditetapkan oleh Mentor.</span>
                    @endif
                </div>
            </div>

            <!-- AREA BAWAH -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-4">
                
                <!-- KALENDER -->
                <div :class="darkMode ? 'glass-dark border-white/20' : 'bg-white border border-gray-100 shadow-sm'" class="lg:col-span-2 rounded-xl p-5 select-none transition-all duration-500">
                    <div class="mb-5 border-b border-white/5 pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold">Kalender Laporan Harian</h3>
                            <p class="text-xs opacity-50 mt-0.5">Klik tanggal untuk mengisi/melihat laporan.</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-md" x-text="monthNames[month] + ' ' + year"></span>
                            <div class="flex gap-1">
                                <button @click="prevMonth()" class="p-1.5 rounded-lg border border-white/10" :class="darkMode ? 'text-white' : 'text-gray-400'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="2.5"></path></svg></button>
                                <button @click="nextMonth()" class="p-1.5 rounded-lg border border-white/10" :class="darkMode ? 'text-white' : 'text-gray-400'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2.5"></path></svg></button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 mb-2 text-center">
                        <template x-for="day in dayNames"><div class="text-[10px] font-extrabold uppercase tracking-widest opacity-40" x-text="day"></div></template>
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="blank in blankDays"><div class="h-16"></div></template>
                        <template x-for="date in no_of_days">
                            <div @click="openModal(date)"
                                 class="h-16 border rounded-lg flex flex-col items-center justify-center cursor-pointer transition relative group"
                                 :class="{
                                    'bg-indigo-600 border-indigo-400 text-white shadow-xl shadow-indigo-500/40': isToday(date),
                                    'bg-white/5 border-white/10 text-white': darkMode && !isToday(date) && !isFuture(date),
                                    'hover:bg-gray-50 border-gray-100': !darkMode && !isToday(date) && !isFuture(date),
                                    'opacity-30 bg-slate-900/40 border-white/5': darkMode && isFuture(date),
                                    'opacity-40 cursor-not-allowed': !darkMode && isFuture(date)
                                 }">
                                <span class="text-xs font-bold" :class="darkMode || isToday(date) ? 'text-white' : 'text-slate-700'" x-text="date"></span>
                                <template x-if="hasReport(date)">
                                    <span class="mt-2 w-1.5 h-1.5 rounded-full ring-1 ring-white" :class="getStatusColor(date)"></span>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="mt-5 pt-3 border-t border-white/5 flex gap-4 justify-center text-[9px] font-bold uppercase opacity-60">
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span> Hadir</div>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Revisi</div>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Izin</div>
                        <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-700"></span> Alpha</div>
                    </div>
                </div>

                <!-- SIDE LIST -->
                <div :class="darkMode ? 'glass-dark border-white/20' : 'bg-white border border-gray-100 shadow-sm'" class="lg:col-span-1 rounded-xl p-5 h-fit transition-all duration-500">
                    <h3 class="text-lg font-bold mb-4">Aktivitas Terakhir</h3>
                    <div class="space-y-4">
                        @forelse($reports->take(4) as $report)
                        <div class="flex gap-3 pb-3 border-b border-white/5 last:border-0 last:pb-0">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold shadow-sm
                                    {{ $report->status == 'approved' ? 'bg-green-100 text-green-700' : 
                                      ($report->status == 'revision' ? 'bg-yellow-100 text-yellow-700' : 
                                      ($report->status == 'rejected' ? 'bg-red-100 text-red-700' : 
                                      ($report->status == 'permit' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'))) }}">
                                    @if($report->status == 'approved') ✓ @elseif($report->status == 'revision') ✎ @else .. @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <p class="text-[10px] opacity-50">{{ \Carbon\Carbon::parse($report->tanggal)->format('d M Y') }}</p>
                                    <span class="text-[8px] px-2 py-0.5 rounded-full font-bold uppercase transition" :class="darkMode ? 'bg-white/10 text-white' : 'bg-slate-100 text-slate-600'">{{ $report->status }}</span>
                                </div>
                                <p class="text-xs font-bold line-clamp-2 leading-snug">{{ $report->aktivitas }}</p>
                            </div>
                        </div>
                        @empty <p class="text-gray-400 text-xs italic">Belum ada data.</p> @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= MODAL COMPACT (GAYA REFERENSI GAMBAR) ================= -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="showModal" @click.away="showModal = false" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="relative w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl transition-all duration-500 border"
                     :class="darkMode ? 'bg-[#0f172a] border-white/20 text-white' : 'bg-white border-slate-100 text-slate-800'">
                    
                    <h3 class="text-2xl font-bold mb-8" x-text="mode === 'create' ? 'Input Laporan' : 'Detail Laporan'"></h3>

                    <form :action="mode === 'edit' ? '/reports/content/' + currentReportId : '{{ route('reports.store') }}'" method="POST" enctype="multipart/form-data">
                        @csrf
                        <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>
                        <input type="hidden" name="tanggal" :value="selectedDateFormatted">

                        <!-- Input Tanggal -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold mb-2 ml-1 opacity-70">Tanggal</label>
                            <input type="text" :value="selectedDateDisplay" readonly 
                                   class="w-full rounded-2xl border-0 py-4 px-5 text-base font-bold text-indigo-500 shadow-inner cursor-not-allowed"
                                   :class="darkMode ? 'bg-white/10' : 'bg-[#F1F3F6]'">
                        </div>

                        <!-- Status Kehadiran -->
                        <div class="mb-6" x-show="mode === 'create'">
                            <label class="block text-sm font-semibold mb-2 ml-1 opacity-70">Status Kehadiran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer border-2 rounded-2xl p-4 flex items-center gap-4 transition-all" 
                                       :class="attendanceType === 'present' ? 'border-indigo-500 bg-indigo-50/10' : (darkMode ? 'border-white/5 opacity-40' : 'border-slate-100')">
                                    <input type="radio" name="attendance_type" value="present" class="hidden" x-model="attendanceType">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="attendanceType === 'present' ? 'border-indigo-600' : 'border-slate-300'">
                                        <div class="w-3 h-3 rounded-full bg-indigo-600" x-show="attendanceType === 'present'"></div>
                                    </div>
                                    <span class="text-sm font-bold">Hadir</span>
                                </label>
                                <label class="cursor-pointer border-2 rounded-2xl p-4 flex items-center gap-4 transition-all" 
                                       :class="attendanceType === 'permit' ? 'border-blue-500 bg-blue-50/10' : (darkMode ? 'border-white/5 opacity-40' : 'border-slate-100')">
                                    <input type="radio" name="attendance_type" value="permit" class="hidden" x-model="attendanceType">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="attendanceType === 'permit' ? 'border-blue-500' : 'border-slate-300'">
                                        <div class="w-3 h-3 rounded-full bg-blue-500" x-show="attendanceType === 'permit'"></div>
                                    </div>
                                    <span class="text-sm font-bold">Izin / Sakit</span>
                                </label>
                            </div>
                        </div>

                        <!-- Detail Aktivitas -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold mb-2 ml-1 opacity-70" x-text="attendanceType === 'permit' ? 'Alasan Ketidakhadiran' : 'Detail Aktivitas'"></label>
                            <textarea name="aktivitas" rows="5" required x-model="currentActivity" :disabled="mode === 'read'"
                                      class="w-full rounded-[1.5rem] p-5 text-base font-medium border-2 focus:ring-4 transition-all duration-300"
                                      :class="darkMode ? 'bg-slate-800 border-white/5 text-white' : 'bg-white border-slate-100 text-slate-800'">
                            </textarea>
                        </div>

                        <!-- Upload Gambar -->
                        <div class="mb-6" x-show="mode === 'create' || mode === 'edit'">
                            <label class="block text-[10px] font-black uppercase tracking-widest opacity-50 mb-2 ml-1">Lampiran Foto (Opsional)</label>
                            <label class="relative flex items-center justify-center w-full p-4 border-2 border-dashed rounded-2xl cursor-pointer hover:bg-slate-50 transition" :class="darkMode ? 'border-white/10 hover:bg-white/5' : 'border-slate-200'">
                                <span class="text-xs font-bold opacity-50">Pilih Dokumen / Foto</span>
                                <input type="file" name="gambar" class="absolute inset-0 opacity-0 cursor-pointer">
                            </label>
                        </div>

                        <!-- Lihat Gambar -->
                        <template x-if="mode === 'read' && currentImage">
                            <div class="mb-8 pt-2">
                                <a :href="currentImage" target="_blank" class="flex items-center justify-center gap-2 w-full py-4 bg-indigo-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 transition shadow-xl shadow-indigo-200/50">
                                    Lihat Lampiran Bukti
                                </a>
                            </div>
                        </template>

                        <!-- Tombol Footer -->
                        <div class="flex justify-end gap-4">
                            <button type="button" @click="showModal = false" class="px-8 py-3 rounded-2xl text-sm font-bold border-2 transition" :class="darkMode ? 'border-white/20 text-white hover:bg-white/10' : 'border-slate-200 text-slate-500'">Tutup</button>
                            <button type="submit" x-show="mode !== 'read'" class="px-10 py-3 rounded-2xl bg-indigo-600 text-white text-sm font-bold shadow-xl shadow-indigo-500/50 hover:bg-indigo-700 transition transform active:scale-95">
                                <span x-text="mode === 'edit' ? 'Simpan Revisi' : 'Simpan Laporan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- LOGIKA AlpineJS -->
    <script>
        function calendarApp(serverReports) {
            return {
                darkMode: localStorage.getItem('theme') === 'dark',
                month: new Date().getMonth(), year: new Date().getFullYear(),
                no_of_days: [], blankDays: [], dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                reports: serverReports, showModal: false, selectedDay: null, mode: 'create', currentActivity: '', currentFeedback: '', currentStatus: '', currentReportId: null, attendanceType: 'present', currentImage: null,

                init() { this.getNoOfDays(); },
                toggleTheme() { this.darkMode = !this.darkMode; localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); },
                nextMonth() { this.month++; if (this.month > 11) { this.month = 0; this.year++; } this.getNoOfDays(); },
                prevMonth() { this.month--; if (this.month < 0) { this.month = 11; this.year--; } this.getNoOfDays(); },
                getNoOfDays() {
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    let dayOfWeek = new Date(this.year, this.month).getDay();
                    this.blankDays = Array.from({length: dayOfWeek}, (_, i) => i + 1);
                    this.no_of_days = Array.from({length: daysInMonth}, (_, i) => i + 1);
                },
                formatDate(day) { return this.year + '-' + String(this.month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0'); },
                hasReport(day) { return this.reports.hasOwnProperty(this.formatDate(day)); },
                getStatusColor(day) {
                    let data = this.reports[this.formatDate(day)];
                    if(!data) return '';
                    const colors = { 'approved': 'bg-green-500 shadow-green-500/50', 'rejected': 'bg-red-500 shadow-red-500/50', 'revision': 'bg-yellow-400 shadow-yellow-400/50', 'permit': 'bg-blue-500 shadow-blue-500/50', 'alpha': 'bg-red-700 shadow-red-700/50' };
                    return colors[data.status] || 'bg-slate-400';
                },
                translateStatus(status) {
                    const trans = { 'approved': 'DITERIMA', 'revision': 'BUTUH REVISI', 'rejected': 'DITOLAK', 'permit': 'IZIN', 'alpha': 'ALPHA', 'pending': 'MENUNGGU' };
                    return trans[status] || status.toUpperCase();
                },
                isToday(day) { const d = new Date(this.year, this.month, day); return new Date().toDateString() === d.toDateString(); },
                isFuture(day) { const today = new Date(); today.setHours(0,0,0,0); return new Date(this.year, this.month, day) > today; },
                openModal(day) { 
                    if (this.isFuture(day)) return; 
                    this.selectedDay = day;
                    let reportData = this.reports[this.formatDate(day)];
                    if (reportData) {
                        this.currentReportId = reportData.id; this.currentActivity = reportData.aktivitas; this.currentFeedback = reportData.komentar; this.currentStatus = reportData.status; this.currentImage = reportData.gambar;
                        this.mode = (reportData.status === 'revision') ? 'edit' : 'read';
                    } else {
                        this.mode = 'create'; this.currentActivity = ''; this.attendanceType = 'present'; this.currentReportId = null; this.currentImage = null;
                    }
                    this.showModal = true; 
                },
                get selectedDateFormatted() { return this.formatDate(this.selectedDay); },
                get selectedDateDisplay() { if(!this.selectedDay) return ''; return this.selectedDay + ' ' + this.monthNames[this.month] + ' ' + this.year; }
            }
        }
    </script>
</x-app-layout>