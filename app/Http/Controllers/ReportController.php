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

        // 1. LOGIKA SUPER ADMIN
        if ($user->role === 'super_admin') {
            $mentorCount = User::where('role', 'mentor')->count();
            $internCount = User::where('role', 'intern')->count();
            $pendingCount = Report::where('status', 'pending')->count();
            $stats = [
                'approved' => Report::where('status','approved')->count(),
                'revision' => Report::where('status','revision')->count(),
                'rejected' => Report::where('status','rejected')->count(),
                'permit'   => Report::where('status','permit')->count(),
                'alpha'    => Report::where('status','alpha')->count(),
                'pending'  => $pendingCount,
            ];
            $latestReports = Report::with('user')->latest()->take(8)->get();
            $pendingReports = Report::where('status','pending')->get();
            return view('superadmin.dashboard', compact('mentorCount','internCount','stats','latestReports','pendingReports'));
        }

        // 2. LOGIKA MENTOR
        if ($user->role === 'mentor') {
            // Mentor hanya melihat laporan 'pending' dari peserta yang dia bimbing (mentor_id)
            $pendingReports = Report::with('user')
                ->whereHas('user', function($query) use ($user) {
                    $query->where('mentor_id', $user->id);
                })
                ->where('status', 'pending')
                ->latest()
                ->get();

            return view('dashboard_mentor', compact('pendingReports'));
        } 
        
        // 3. LOGIKA INTERN (PESERTA)
        else {
            $reports = Report::where('user_id', $user->id)->latest()->get();

            // Mapping data untuk kalender Alpine.js
            $calendarData = $reports->mapWithKeys(function ($item) {
                return [$item->tanggal => [
                    'id'        => $item->id,
                    'status'    => $item->status,
                    'aktivitas' => $item->aktivitas,
                    'komentar'  => $item->komentar_mentor,
                    'gambar'    => $item->gambar ? asset('storage/' . $item->gambar) : null
                ]];
            });

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
     * Menu Riwayat Laporan (Daftar Peserta)
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        // Ambil jumlah pending untuk badge di sidebar
        $pendingReports = Report::where('status', 'pending');
        if ($user->role === 'mentor') {
            $pendingReports->whereHas('user', function($q) use ($user) { $q->where('mentor_id', $user->id); });
        }
        $pendingReports = $pendingReports->get();

        // Query Daftar Peserta
        $query = User::where('role', 'intern')->withCount('reports');

        // JIKA MENTOR: Hanya lihat peserta miliknya
        if ($user->role === 'mentor') {
            $query->where('mentor_id', $user->id);
        }
        // JIKA SUPER ADMIN: Bisa lihat SEMUA peserta (tidak perlu filter mentor_id)

        // Fitur Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $interns = $query->latest()->paginate(8)->withQueryString();

        return view('mentor.reports_history_list', compact('pendingReports', 'interns'));
    }

    /**
     * Detail Riwayat Laporan Peserta Tertentu
     */
    public function userHistory(User $user)
    {
        $me = Auth::user();

        // Keamanan: Mentor tidak boleh mengintip peserta mentor lain
        if ($me->role === 'mentor' && $user->mentor_id !== $me->id) {
            abort(403, 'Peserta ini bukan bimbingan Anda.');
        }

        $pendingReports = Report::where('status', 'pending');
        if ($me->role === 'mentor') {
            $pendingReports->whereHas('user', function($q) use ($me) { $q->where('mentor_id', $me->id); });
        }
        $pendingReports = $pendingReports->get();
        
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
            'gambar'    => 'nullable|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('reports', 'public');
        }

        $status = ($request->attendance_type === 'permit') ? 'permit' : 'pending';

        Report::create([
            'user_id'   => Auth::id(),
            'tanggal'   => $request->tanggal,
            'aktivitas' => $request->aktivitas,
            'gambar'    => $path,
            'status'    => $status 
        ]);

        return redirect()->back()->with('success', 'Laporan berhasil dikirim!');
    }

    /**
     * Update Isi Laporan (Revisi)
     */
    public function updateContent(Request $request, Report $report)
    {
        if ($report->user_id !== Auth::id()) abort(403);
        if ($report->status !== 'revision') abort(403);

        $request->validate(['aktivitas' => 'required|string', 'gambar' => 'nullable|mimes:jpg,jpeg,png,webp|max:5120']);

        $data = ['aktivitas' => $request->aktivitas, 'status' => 'pending'];

        if ($request->hasFile('gambar')) {
            if ($report->gambar) Storage::disk('public')->delete($report->gambar);
            $data['gambar'] = $request->file('gambar')->store('reports', 'public');
        }

        $report->update($data);
        return redirect()->back()->with('success', 'Perbaikan berhasil dikirim.');
    }

    /**
     * Update Status Laporan (Mentor & Super Admin)
     */
    public function update(Request $request, Report $report)
    {
        // Hanya Mentor & Super Admin yang bisa update status
        if (!in_array(Auth::user()->role, ['mentor', 'super_admin'])) abort(403);

        $request->validate([
            'status' => 'required|in:approved,revision,rejected,alpha',
            'komentar_mentor' => 'nullable|string'
        ]);

        $report->update([
            'status'          => $request->status,
            'komentar_mentor' => $request->komentar_mentor
        ]);

        return redirect()->back()->with('success', 'Status diperbarui.');
    }

    /**
     * Hapus Laporan (Mentor & Super Admin)
     */
    public function destroy(Report $report)
    {
        if (!in_array(Auth::user()->role, ['mentor', 'super_admin'])) abort(403);

        if ($report->gambar) {
            Storage::disk('public')->delete($report->gambar);
        }

        $report->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus.');
    }
}
