<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    /**
     * Tampilkan Daftar Seluruh Mentor (Admin)
     */
    public function indexMentors(Request $request)
    {
        // Ambil data user dengan role mentor beserta jumlah anak bimbingannya
        $query = User::where('role', 'mentor')->withCount('interns')->latest();

        // Fitur pencarian: nama / email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $mentors = $query->paginate(12)->withQueryString();

        // Ambil data pending untuk badge notifikasi di sidebar agar tidak error
        $pendingReports = Report::where('status', 'pending')->get();

        return view('superadmin.mentors_index', compact('mentors', 'pendingReports'));
    }

    /**
     * Simpan Mentor Baru atau Update Mentor Lama
     */
    public function storeMentor(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $request->id,
            'password' => $request->id ? 'nullable|min:8' : 'required|min:8',
        ]);

        if ($request->id) {
            // Logika UPDATE
            $mentor = User::findOrFail($request->id);
            $mentor->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $mentor->password
            ]);
            $message = 'Data Mentor berhasil diperbarui.';
        } else {
            // Logika CREATE
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'mentor' // Otomatis set sebagai mentor
            ]);
            $message = 'Mentor baru berhasil didaftarkan.';
        }

        return back()->with('success', $message);
    }

    /**
     * Hapus Akun Mentor
     */
    public function destroyMentor(User $user)
    {
        // Pastikan yang dihapus memang mentor
        if ($user->role !== 'mentor') {
            return back()->with('error', 'User tersebut bukan seorang mentor.');
        }

        // Lepaskan hubungan mentor_id pada peserta magang bimbingannya (set null)
        User::where('mentor_id', $user->id)->update(['mentor_id' => null]);

        $user->delete();

        return back()->with('success', 'Akun mentor berhasil dihapus dari sistem.');
    }

    /**
     * Tampilkan Seluruh Peserta Magang (Global Monitoring)
     */
    public function allInterns(Request $request)
    {
        // Ambil semua user role intern beserta info mentornya
        $query = User::where('role', 'intern')->with('mentor')->latest();

        // Fitur pencarian: nama / email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $interns = $query->paginate(15)->withQueryString();

        // Ambil data pending untuk badge notifikasi di sidebar agar tidak error
        $pendingReports = Report::where('status', 'pending')->get();

        return view('superadmin.interns_all', compact('interns', 'pendingReports'));
    }

    /**
     * Tampilkan seluruh peserta yang dibimbing oleh mentor tertentu
     */
    public function mentorInterns(User $mentor, Request $request)
    {
        if ($mentor->role !== 'mentor') {
            abort(404);
        }

        $query = User::where('role', 'intern')
            ->where('mentor_id', $mentor->id)
            ->with('mentor')
            ->latest();

        // Fitur pencarian: nama / email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $interns = $query->paginate(15)->withQueryString();

        $pendingReports = Report::where('status', 'pending')->get();

        return view('superadmin.mentor_interns', compact('mentor', 'interns', 'pendingReports'));
    }
}
