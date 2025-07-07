<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\NilaiAbsensiController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GuruKelasController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengajaranController;
use App\Http\Controllers\JadwalController;

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
    Route::resource('tahun-ajaran', TahunAjaranController::class)->except('show');
    Route::resource('kelas', KelasController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('pengajaran', PengajaranController::class)->except('show');
    Route::resource('jadwal', JadwalController::class)->except('show');
    Route::get('/siswa-export', [SiswaController::class, 'export'])->name('siswa.export');
});

// Fitur yang dapat diakses oleh admin dan guru
Route::middleware(['auth', 'role:admin,guru'])->group(function () {
    Route::get('/nilai-absensi', [\App\Http\Controllers\NilaiAbsensiController::class, 'index'])->name('nilai.absensi');
    Route::resource('absensi', AbsensiController::class)->except('show');
    Route::resource('penilaian', PenilaianController::class)->only('index','create','store','destroy');
    Route::redirect('/absensi/harian', '/absensi')->name('absensi.harian');
    Route::get('/absensi/pelajaran', [AbsensiController::class, 'pelajaran'])->name('absensi.pelajaran');
    Route::get('/absensi/pelajaran/{jadwal}', [AbsensiController::class, 'pelajaranForm'])->name('absensi.pelajaran.form');
    Route::post('/absensi/pelajaran/{jadwal}', [AbsensiController::class, 'pelajaranStore'])->name('absensi.pelajaran.store');
    Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
    Route::get('/absensi/rekap/export', [AbsensiController::class, 'exportRekap'])->name('absensi.rekap.export');
});

// Halaman guru untuk melihat siswa di kelasnya
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/kelas-saya', [GuruKelasController::class, 'index'])->name('guru.kelas');
    Route::get('/input-nilai', [\App\Http\Controllers\InputNilaiController::class, 'index'])->name('input-nilai.index');
    Route::get('/input-nilai/{mapel}', [\App\Http\Controllers\InputNilaiController::class, 'kelas'])->name('input-nilai.kelas');
    Route::get('/input-nilai/{mapel}/{kelas}', [\App\Http\Controllers\InputNilaiController::class, 'nilai'])->name('input-nilai.nilai');
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
    Route::get('/saya/absen', [StudentController::class, 'absenForm'])->name('student.absen.form');
    Route::post('/saya/absen', [StudentController::class, 'absen'])->name('student.absen');
    Route::get('/saya/nilai-absensi', [StudentController::class, 'nilaiAbsensi'])->name('student.nilai.absensi');
});

require __DIR__.'/auth.php';
