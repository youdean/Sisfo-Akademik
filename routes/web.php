<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\MapelController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\TahunAjaranController;
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
    Route::resource('absensi', AbsensiController::class)->except('show');
    Route::resource('penilaian', PenilaianController::class)->only('index','create','store','destroy');
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
    Route::get('/input-nilai/{mapel}/{kelas}', [\App\Http\Controllers\InputNilaiController::class, 'opsi'])->name('input-nilai.opsi');
    Route::get('/input-nilai/{mapel}/{kelas}/absensi', [\App\Http\Controllers\InputNilaiController::class, 'absensi'])->name('input-nilai.nilai');
    Route::get('/input-nilai/{mapel}/{kelas}/tugas', [\App\Http\Controllers\InputNilaiController::class, 'tugasForm'])->name('input-nilai.tugas.form');
    Route::post('/input-nilai/{mapel}/{kelas}/tugas', [\App\Http\Controllers\InputNilaiController::class, 'tugasStore'])->name('input-nilai.tugas.store');
    Route::get('/input-nilai/{mapel}/{kelas}/tugas-list', [\App\Http\Controllers\InputNilaiController::class, 'tugasList'])->name('input-nilai.tugas.list');
    Route::get('/input-nilai/{mapel}/{kelas}/tugas/{nama}/edit', [\App\Http\Controllers\InputNilaiController::class, 'tugasEditForm'])->name('input-nilai.tugas.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/tugas/{nama}', [\App\Http\Controllers\InputNilaiController::class, 'tugasUpdate'])->name('input-nilai.tugas.update');

    Route::get('/input-nilai/{mapel}/{kelas}/pts-pat', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatForm'])->name('input-nilai.pts-pat.form');
    Route::post('/input-nilai/{mapel}/{kelas}/pts-pat', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatStore'])->name('input-nilai.pts-pat.store');
    Route::get('/input-nilai/{mapel}/{kelas}/pts-pat-nilai', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatList'])->name('input-nilai.pts-pat.list');
    Route::get('/input-nilai/{mapel}/{kelas}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsForm'])->name('input-nilai.pts.form');
    Route::post('/input-nilai/{mapel}/{kelas}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsStore'])->name('input-nilai.pts.store');
    Route::get('/input-nilai/{mapel}/{kelas}/pts-nilai', [\App\Http\Controllers\InputNilaiController::class, 'ptsList'])->name('input-nilai.pts.list');
    Route::get('/input-nilai/{mapel}/{kelas}/pts/edit', [\App\Http\Controllers\InputNilaiController::class, 'ptsEditForm'])->name('input-nilai.pts.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsUpdate'])->name('input-nilai.pts.update');
    Route::get('/input-nilai/{mapel}/{kelas}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patForm'])->name('input-nilai.pat.form');
    Route::post('/input-nilai/{mapel}/{kelas}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patStore'])->name('input-nilai.pat.store');
    Route::get('/input-nilai/{mapel}/{kelas}/pat-nilai', [\App\Http\Controllers\InputNilaiController::class, 'patList'])->name('input-nilai.pat.list');
    Route::get('/input-nilai/{mapel}/{kelas}/pat/edit', [\App\Http\Controllers\InputNilaiController::class, 'patEditForm'])->name('input-nilai.pat.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patUpdate'])->name('input-nilai.pat.update');
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
});

require __DIR__.'/auth.php';
