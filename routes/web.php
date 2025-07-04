<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RaporController;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Custom dashboard (redirect setelah login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// CRUD menu - hanya untuk user dengan role admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('guru', GuruController::class)->except('show');
    Route::resource('siswa', SiswaController::class)->except('show');
    Route::resource('mapel', MapelController::class)->except('show');
    Route::resource('nilai', NilaiController::class)->except('show');
    Route::resource('absensi', AbsensiController::class)->except('show');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/rekap/export', [AbsensiController::class, 'exportRekap'])->name('absensi.rekap.export');
    
    Route::get('/rapor/{siswa}', [RaporController::class, 'cetak'])->name('rapor.cetak');
    Route::get('/siswa-export', [SiswaController::class, 'export'])->name('siswa.export');



    // Profile edit
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
