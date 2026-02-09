<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MentorController;
use App\Http\Middleware\IsMentor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama (Otomatis mendeteksi Role)
    Route::get('/dashboard', [ReportController::class, 'index'])->name('dashboard');

    // Group Khusus Mentor
    Route::middleware([IsMentor::class])->group(function () {
        
       // 1. Halaman Utama Riwayat (Daftar Nama Peserta)
    Route::get('/mentor/history', [ReportController::class, 'history'])->name('mentor.history');
    
    // 2. Halaman Detail Riwayat (Laporan per Peserta)
    Route::get('/mentor/history/user/{user}', [ReportController::class, 'userHistory'])->name('mentor.history.user');

        // Fitur CRUD Peserta
        Route::get('/mentor/interns', [MentorController::class, 'indexInterns'])->name('mentor.interns.index');
        Route::get('/mentor/interns/create', [MentorController::class, 'createIntern'])->name('mentor.interns.create');
        Route::post('/mentor/interns', [MentorController::class, 'storeIntern'])->name('mentor.interns.store');
        Route::get('/mentor/interns/{user}/edit', [MentorController::class, 'editIntern'])->name('mentor.interns.edit');
        Route::put('/mentor/interns/{user}', [MentorController::class, 'updateIntern'])->name('mentor.interns.update');
        Route::delete('/mentor/interns/{user}', [MentorController::class, 'destroyIntern'])->name('mentor.interns.destroy');

        // Fitur Indikator
        Route::get('/mentor/performance', [MentorController::class, 'performanceIndex'])->name('mentor.performance');
        Route::patch('/mentor/performance/{user}', [MentorController::class, 'performanceUpdate'])->name('mentor.performance.update');
    });

    // Logbook & Laporan (Aksi)
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::put('/reports/content/{report}', [ReportController::class, 'updateContent'])->name('reports.updateContent');
    Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';