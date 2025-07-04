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
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuruKelasController;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Custom dashboard (redirect setelah login)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Manajemen data khusus admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('guru', GuruController::class)->except('show');
    Route::resource('siswa', SiswaController::class)->except('show');
    Route::resource('mapel', MapelController::class)->except('show');
    Route::get('/siswa-export', [SiswaController::class, 'export'])->name('siswa.export');
});

// Fitur yang dapat diakses oleh admin dan guru
Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    Route::resource('nilai', NilaiController::class)->except('show');
    Route::resource('absensi', AbsensiController::class)->except('show');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/rekap/export', [AbsensiController::class, 'exportRekap'])->name('absensi.rekap.export');
    Route::get('/rapor/{siswa}', [RaporController::class, 'cetak'])->name('rapor.cetak');
});

// Halaman guru untuk melihat siswa di kelasnya
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/kelas-saya', [GuruKelasController::class, 'index'])->name('guru.kelas');
});

// Profile dapat diakses oleh semua user yang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes khusus siswa untuk melihat data sendiri
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/saya', [StudentController::class, 'profil'])->name('student.profile');
    Route::get('/saya/absensi', [StudentController::class, 'absensi'])->name('student.absensi');
    Route::get('/saya/nilai', [StudentController::class, 'nilai'])->name('student.nilai');
    Route::get('/saya/rapor', [StudentController::class, 'rapor'])->name('student.rapor');
});

require __DIR__.'/auth.php';
