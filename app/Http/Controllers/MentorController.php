<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MentorController extends Controller
{
    // 1. Tampilkan Daftar Peserta (READ)
    public function indexInterns(Request $request)
    {
        // Fitur pencarian sederhana
        $query = User::where('role', 'intern');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $interns = $query->latest()->paginate(10); // 10 per halaman

        return view('mentor.interns.index', compact('interns'));
    }

    // 2. Tampilkan Form Tambah (CREATE VIEW)
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
            // VALIDASI BARU
            'intern_location'   => ['nullable', 'string', 'max:255'],
            'intern_start_date' => ['nullable', 'date'],
            'intern_end_date'   => ['nullable', 'date'],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'intern',
            // PENYIMPANAN DATA BARU
            'intern_location'   => $request->intern_location,
            'intern_start_date' => $request->intern_start_date,
            'intern_end_date'   => $request->intern_end_date,
        ]);

        return redirect()->route('mentor.interns.index')->with('success', 'Peserta magang berhasil ditambahkan!');
    }

    // 4. Tampilkan Form Edit (EDIT VIEW)
    public function editIntern(User $user)
    {
        // Pastikan yang diedit adalah intern
        if($user->role !== 'intern') abort(404);
        
        return view('mentor.interns.edit', compact('user'));
    }

    // 5. Proses Update (UPDATE)
    public function updateIntern(Request $request, User $user)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            // VALIDASI BARU
            'intern_location'   => ['nullable', 'string', 'max:255'],
            'intern_start_date' => ['nullable', 'date'],
            'intern_end_date'   => ['nullable', 'date'],
        ]);

        $data = [
            'name'              => $request->name,
            'email'             => $request->email,
            // UPDATE DATA BARU
            'intern_location'   => $request->intern_location,
            'intern_start_date' => $request->intern_start_date,
            'intern_end_date'   => $request->intern_end_date,
        ];

        // Jika password diisi, update password. Jika kosong, biarkan password lama.
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
        if($user->role !== 'intern') abort(403);
        
        $user->delete();
        return redirect()->back()->with('success', 'Peserta magang berhasil dihapus.');
    }

    // --- LOGIKA INDIKATOR KINERJA ---
    public function performanceIndex()
    {
        $interns = User::where('role', 'intern')->get();
        return view('mentor.performance_index', compact('interns'));
    }

    public function performanceUpdate(Request $request, User $user)
    {
        $request->validate(['performance_target' => 'required|string']);
        $user->update(['performance_target' => $request->performance_target]);
        return redirect()->back()->with('success', 'Indikator kinerja berhasil disimpan!');
    }
}