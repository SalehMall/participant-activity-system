<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\SuperAdminController; // Import Controller baru
use Illuminate\Support\Facades\Route;

// --- HALAMAN PUBLIK ---
Route::get('/', function () {
    return view('auth.login');
});

// --- GRUP LOGGED IN (Sudah Login) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Utama (Otomatis mendeteksi Role di Controller)
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');

    // ==========================================
    // ROLE: SUPER ADMIN (Mengelola Mentor)
    // ==========================================
    Route::middleware(['super_admin'])->group(function () {
        // CRUD Mentor
        Route::get('/super/mentors', [SuperAdminController::class, 'indexMentors'])->name('super.mentors.index');
        Route::post('/super/mentors', [SuperAdminController::class, 'storeMentor'])->name('super.mentors.store');
        Route::put('/super/mentors/{user}', [SuperAdminController::class, 'updateMentor'])->name('super.mentors.update');
        Route::delete('/super/mentors/{user}', [SuperAdminController::class, 'destroyMentor'])->name('super.mentors.destroy');
        Route::get('/super/mentors/{mentor}/interns', [SuperAdminController::class, 'mentorInterns'])->name('super.mentors.interns');

        // Monitoring Global (Lihat seluruh peserta magang dari semua mentor)
        Route::get('/super/interns', [SuperAdminController::class, 'allInterns'])->name('super.interns.all');
    });

    // ==========================================
    // ROLE: MENTOR (Mengelola Laporan & Peserta)
    // ==========================================
    Route::middleware(['mentor'])->group(function () {
        // Dashboard Mentor
        Route::get('/mentor/dashboard', [MentorController::class, 'dashboard'])->name('mentor.dashboard');
        // 1. Verifikasi Laporan & Riwayat
        Route::get('/mentor/history', [ReportController::class, 'history'])->name('mentor.history');
        Route::get('/mentor/history/user/{user}', [ReportController::class, 'userHistory'])->name('mentor.history.user');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
        Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

        // 2. Fitur CRUD Peserta Magang
        Route::get('/mentor/interns', [MentorController::class, 'indexInterns'])->name('mentor.interns.index');
        Route::get('/mentor/interns/create', [MentorController::class, 'createIntern'])->name('mentor.interns.create');
        Route::post('/mentor/interns', [MentorController::class, 'storeIntern'])->name('mentor.interns.store');
        Route::get('/mentor/interns/{user}/edit', [MentorController::class, 'editIntern'])->name('mentor.interns.edit');
        Route::put('/mentor/interns/{user}', [MentorController::class, 'updateIntern'])->name('mentor.interns.update');
        Route::delete('/mentor/interns/{user}', [MentorController::class, 'destroyIntern'])->name('mentor.interns.destroy');

        // 3. Fitur Indikator Kinerja
        Route::get('/mentor/performance', [MentorController::class, 'performanceIndex'])->name('mentor.performance');
        Route::patch('/mentor/performance/{user}', [MentorController::class, 'performanceUpdate'])->name('mentor.performance.update');
    });

    // ==========================================
    // ROLE: INTERN (Peserta Magang)
    // ==========================================
    // (Aksi kirim laporan ditaruh di sini agar hanya pemilik yang bisa akses via logic controller)
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::put('/reports/content/{report}', [ReportController::class, 'updateContent'])->name('reports.updateContent');

    // ==========================================
    // PENGATURAN PROFIL (Semua Role)
    // ==========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
