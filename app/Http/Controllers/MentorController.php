<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // WAJIB DIIMPORT
use Illuminate\Validation\Rules;

class MentorController extends Controller
{
    // 0. Dashboard Mentor
    public function dashboard()
    {
        $mentorId = Auth::id();
        $internCount = User::where('role','intern')->where('mentor_id',$mentorId)->count();
        $baseQuery = \App\Models\Report::whereHas('user', function($q) use ($mentorId) {
            $q->where('mentor_id', $mentorId);
        });
        $stats = [
            'approved' => (clone $baseQuery)->where('status','approved')->count(),
            'revision' => (clone $baseQuery)->where('status','revision')->count(),
            'rejected' => (clone $baseQuery)->where('status','rejected')->count(),
            'permit'   => (clone $baseQuery)->where('status','permit')->count(),
            'alpha'    => (clone $baseQuery)->where('status','alpha')->count(),
            'pending'  => (clone $baseQuery)->where('status','pending')->count(),
        ];
        $latestReports = (clone $baseQuery)->with('user')->latest()->take(8)->get();
        $pendingReports = (clone $baseQuery)->where('status','pending')->latest()->take(8)->get();
        return view('mentor.dashboard', compact('internCount','stats','latestReports','pendingReports'));
    }
    // 1. Tampilkan Daftar Peserta (Hanya milik mentor yang login)
    public function indexInterns(Request $request)
    {
        // Filter: Hanya ambil peserta yang mentor_id-nya adalah ID saya (mentor yang login)
        $query = User::where('role', 'intern')->where('mentor_id', Auth::id());
        
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $interns = $query->latest()->paginate(10);

        return view('mentor.interns.index', compact('interns'));
    }

    // 2. Tampilkan Form Tambah
    public function createIntern()
    {
        return view('mentor.interns.create');
    }

    // 3. Proses Simpan (STORE)
    public function storeIntern(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'intern_location'   => ['nullable', 'string', 'max:255'],
            'intern_start_date' => ['nullable', 'date'],
            'intern_end_date'   => ['nullable', 'date'],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'intern',
            // --- PERBAIKAN DI SINI: Simpan ID Mentor yang sedang login ---
            'mentor_id' => Auth::id(), 
            // --------------------------------------------------------------
            'intern_location'   => $request->intern_location,
            'intern_start_date' => $request->intern_start_date,
            'intern_end_date'   => $request->intern_end_date,
        ]);

        return redirect()->route('mentor.interns.index')->with('success', 'Peserta magang berhasil ditambahkan!');
    }

    // 4. Tampilkan Form Edit
    public function editIntern(User $user)
    {
        // Keamanan: Mentor hanya boleh edit peserta miliknya
        if($user->role !== 'intern' || $user->mentor_id !== Auth::id()) abort(403);
        
        return view('mentor.interns.edit', compact('user'));
    }

    // 5. Proses Update (UPDATE)
    public function updateIntern(Request $request, User $user)
    {
        // Keamanan: Mentor hanya boleh update peserta miliknya
        if($user->mentor_id !== Auth::id()) abort(403);

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'intern_location'   => ['nullable', 'string', 'max:255'],
            'intern_start_date' => ['nullable', 'date'],
            'intern_end_date'   => ['nullable', 'date'],
        ]);

        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            'intern_location'   => $request->intern_location,
            'intern_start_date' => $request->intern_start_date,
            'intern_end_date'   => $request->intern_end_date,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('mentor.interns.index')->with('success', 'Data peserta berhasil diperbarui!');
    }

    // 6. Proses Hapus (DESTROY)
    public function destroyIntern(User $user)
    {
        // Keamanan: Mentor hanya boleh hapus peserta miliknya
        if($user->role !== 'intern' || $user->mentor_id !== Auth::id()) abort(403);
        
        $user->delete();
        return redirect()->back()->with('success', 'Peserta magang berhasil dihapus.');
    }

    // --- LOGIKA INDIKATOR KINERJA ---
    public function performanceIndex()
    {
        // Filter: Hanya ambil peserta milik mentor yang login
        $interns = User::where('role', 'intern')->where('mentor_id', Auth::id())->get();
        return view('mentor.performance_index', compact('interns'));
    }

    public function performanceUpdate(Request $request, User $user)
    {
        // Keamanan: Cek kepemilikan
        if($user->mentor_id !== Auth::id()) abort(403);

        $request->validate(['performance_target' => 'required|string']);
        $user->update(['performance_target' => $request->performance_target]);
        return redirect()->back()->with('success', 'Indikator kinerja berhasil disimpan!');
    }
}
