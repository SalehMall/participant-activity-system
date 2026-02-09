<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Dashboard Utama (Mendeteksi Role)
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'mentor') {
            // --- TAMPILAN MENTOR (Halaman Review Laporan Masuk) ---
            // Hanya mengambil laporan yang statusnya masih 'pending'
            $pendingReports = Report::with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();

            return view('dashboard_mentor', compact('pendingReports'));
        } else {
            // --- TAMPILAN INTERN (Dashboard Kalender & Statistik) ---
            $reports = Report::where('user_id', $user->id)->latest()->get();

            // Mapping data untuk kalender Alpine.js agar bisa menampilkan detail & gambar di modal
            $calendarData = $reports->mapWithKeys(function ($item) {
                return [$item->tanggal => [
                    'id'        => $item->id,
                    'status'    => $item->status,
                    'aktivitas' => $item->aktivitas,
                    'komentar'  => $item->komentar_mentor,
                    'gambar'    => $item->gambar ? asset('storage/' . $item->gambar) : null
                ]];
            });

            // Hitung statistik untuk kotak 5 warna (BBPVP Style)
            $stats = [
                'hadir'   => $reports->where('status', 'approved')->count(),
                'revisi'  => $reports->where('status', 'revision')->count(),
                'ditolak' => $reports->where('status', 'rejected')->count(),
                'izin'    => $reports->where('status', 'permit')->count(),
                'alpha'   => $reports->where('status', 'alpha')->count()
            ];

            return view('dashboard_intern', compact('reports', 'stats', 'calendarData'));
        }
    }

    /**
     * Menu Riwayat Laporan (Halaman Daftar Peserta Magang)
     */
    public function history(Request $request)
{
    $pendingReports = Report::where('status', 'pending')->get();
    
    // Logika Search
    $query = User::where('role', 'intern')->withCount('reports');

    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('email', 'like', '%' . $request->search . '%');
    }

    // Gunakan 8 data per halaman agar pas di layar tanpa scroll panjang
    $interns = $query->latest()->paginate(8)->withQueryString();

    return view('mentor.reports_history_list', compact('pendingReports', 'interns'));
}

    /**
     * Detail Riwayat Laporan Peserta Tertentu
     */
    public function userHistory(User $user)
    {
        // Tetap kirim pendingReports agar UI tidak error
        $pendingReports = Report::where('status', 'pending')->get();
        
        // Ambil laporan milik user ini yang sudah diproses (bukan pending)
        $historyReports = Report::where('user_id', $user->id)
            ->where('status', '!=', 'pending')
            ->latest()
            ->paginate(10);

        return view('mentor.user_history_detail', compact('pendingReports', 'historyReports', 'user'));
    }

    /**
     * Simpan Laporan Baru (Intern)
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'   => 'required|date',
            'aktivitas' => 'required|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
        ]);

        // Proses Upload Gambar
        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('reports', 'public');
        }

        // Logika Status: Jika Izin langsung 'permit', jika Hadir masuk 'pending' untuk direview
        $status = ($request->attendance_type === 'permit') ? 'permit' : 'pending';

        Report::create([
            'user_id'   => Auth::id(),
            'tanggal'   => $request->tanggal,
            'aktivitas' => $request->aktivitas,
            'gambar'    => $path,
            'status'    => $status 
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim ke mentor!');
    }

    /**
     * Update Isi Laporan (Intern memperbaiki laporan yang statusnya REVISI)
     */
    public function updateContent(Request $request, Report $report)
    {
        // Keamanan: Pastikan hanya pemilik yang mengedit dan statusnya memang 'revision'
        if ($report->user_id !== Auth::id()) abort(403);
        if ($report->status !== 'revision') abort(403, 'Laporan tidak dalam masa perbaikan.');

        $request->validate([
            'aktivitas' => 'required|string',
            'gambar'    => 'nullable|image|max:2048',
        ]);

        $data = [
            'aktivitas' => $request->aktivitas,
            'status'    => 'pending' // Kembalikan ke 'pending' agar bisa diperiksa ulang mentor
        ];

        // Ganti gambar jika user mengupload gambar baru
        if ($request->hasFile('gambar')) {
            if ($report->gambar) Storage::disk('public')->delete($report->gambar);
            $data['gambar'] = $request->file('gambar')->store('reports', 'public');
        }

        $report->update($data);

        return redirect()->back()->with('success', 'Laporan revisi telah dikirim kembali.');
    }

    /**
     * Update Status Laporan (Mentor: Terima / Revisi / Tolak)
     */
    public function update(Request $request, Report $report)
    {
        if (Auth::user()->role !== 'mentor') abort(403);

        $request->validate([
            'status' => 'required|in:approved,revision,rejected',
            'komentar_mentor' => 'nullable|string'
        ]);

        $report->update([
            'status'          => $request->status,
            'komentar_mentor' => $request->komentar_mentor
        ]);

        return redirect()->back()->with('success', 'Status laporan peserta berhasil diperbarui.');
    }

    /**
     * Hapus Laporan (Mentor)
     */
    public function destroy(Report $report)
    {
        if (Auth::user()->role !== 'mentor') abort(403);

        // Hapus file gambar dari folder storage agar tidak membebani server
        if ($report->gambar) {
            Storage::disk('public')->delete($report->gambar);
        }

        $report->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus secara permanen.');
    }
}