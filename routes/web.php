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
use App\Http\Controllers\RaporController;

// Landing page - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
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
    Route::post('/jadwal/generate', [JadwalController::class, 'generate'])->name('jadwal.generate');
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
    Route::get('/absensi/session/{jadwal}', [AbsensiController::class, 'session'])->name('absensi.session');
    Route::post('/absensi/session/{jadwal}/start', [AbsensiController::class, 'startSession'])->name('absensi.session.start');
    Route::post('/absensi/session/{jadwal}/end', [AbsensiController::class, 'endSession'])->name('absensi.session.end');
});

// Halaman guru untuk melihat siswa di kelasnya
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/kelas-saya', [GuruKelasController::class, 'index'])->name('guru.kelas');
    Route::get('/nilai-kelas', [GuruKelasController::class, 'nilaiKelas'])->name('guru.nilai-kelas');
    Route::get('/input-nilai', [\App\Http\Controllers\InputNilaiController::class, 'index'])->name('input-nilai.index');
    Route::get('/input-nilai/kelas/{kelas}', [\App\Http\Controllers\InputNilaiController::class, 'mapel'])->name('input-nilai.mapel');
    Route::get('/input-nilai/{mapel}/{kelas}', [\App\Http\Controllers\InputNilaiController::class, 'opsi'])->name('input-nilai.opsi');
    Route::get('/input-nilai/{mapel}/{kelas}/absensi', [\App\Http\Controllers\InputNilaiController::class, 'absensi'])->name('input-nilai.nilai');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/tugas', [\App\Http\Controllers\InputNilaiController::class, 'tugasForm'])->name('input-nilai.tugas.form');
    Route::post('/input-nilai/{mapel}/{kelas}/{semester}/tugas', [\App\Http\Controllers\InputNilaiController::class, 'tugasStore'])->name('input-nilai.tugas.store');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/tugas-list', [\App\Http\Controllers\InputNilaiController::class, 'tugasList'])->name('input-nilai.tugas.list');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/tugas/{nama}/edit', [\App\Http\Controllers\InputNilaiController::class, 'tugasEditForm'])->name('input-nilai.tugas.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/{semester}/tugas/{nama}', [\App\Http\Controllers\InputNilaiController::class, 'tugasUpdate'])->name('input-nilai.tugas.update');

    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pts-pat', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatForm'])->name('input-nilai.pts-pat.form');
    Route::post('/input-nilai/{mapel}/{kelas}/{semester}/pts-pat', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatStore'])->name('input-nilai.pts-pat.store');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pts-pat-nilai', [\App\Http\Controllers\InputNilaiController::class, 'ptsPatList'])->name('input-nilai.pts-pat.list');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsForm'])->name('input-nilai.pts.form');
    Route::post('/input-nilai/{mapel}/{kelas}/{semester}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsStore'])->name('input-nilai.pts.store');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pts-nilai', [\App\Http\Controllers\InputNilaiController::class, 'ptsList'])->name('input-nilai.pts.list');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pts/edit', [\App\Http\Controllers\InputNilaiController::class, 'ptsEditForm'])->name('input-nilai.pts.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/{semester}/pts', [\App\Http\Controllers\InputNilaiController::class, 'ptsUpdate'])->name('input-nilai.pts.update');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patForm'])->name('input-nilai.pat.form');
    Route::post('/input-nilai/{mapel}/{kelas}/{semester}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patStore'])->name('input-nilai.pat.store');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pat-nilai', [\App\Http\Controllers\InputNilaiController::class, 'patList'])->name('input-nilai.pat.list');
    Route::get('/input-nilai/{mapel}/{kelas}/{semester}/pat/edit', [\App\Http\Controllers\InputNilaiController::class, 'patEditForm'])->name('input-nilai.pat.edit');
    Route::put('/input-nilai/{mapel}/{kelas}/{semester}/pat', [\App\Http\Controllers\InputNilaiController::class, 'patUpdate'])->name('input-nilai.pat.update');
});

// Profile dapat diakses oleh semua user yang login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Cetak rapor dapat diakses oleh siswa, wali kelas, dan admin
Route::middleware(['auth', 'role:admin,guru,siswa'])->get('/rapor/cetak/{siswa?}', [RaporController::class, 'cetak'])->name('rapor.cetak');

// Routes khusus siswa untuk melihat data sendiri
Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/saya', [StudentController::class, 'profil'])->name('student.profile');
    Route::get('/saya/absensi', [StudentController::class, 'absensi'])->name('student.absensi');
    Route::get('/saya/nilai', [StudentController::class, 'nilai'])->name('student.nilai');
    Route::get('/saya/jadwal', [StudentController::class, 'jadwal'])->name('student.jadwal');
    Route::get('/saya/jadwal/{jadwal}/absen', [StudentController::class, 'jadwalAbsenForm'])->name('student.jadwal.absen.form');
    Route::post('/saya/jadwal/{jadwal}/absen', [StudentController::class, 'jadwalAbsen'])->name('student.jadwal.absen');
    Route::post('/saya/absensi/check-in', [StudentController::class, 'sessionCheckIn'])->name('student.absensi.checkin');
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
