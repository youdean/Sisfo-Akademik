<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\Pengajaran;
use App\Models\Jadwal;
use App\Models\Penilaian;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()?->role === 'guru') {
            return $this->dashboardGuru();
        }

        if (Auth::user()?->role === 'siswa') {
            return $this->dashboardSiswa();
        }

        $today = Carbon::today();

        $absensiPerHari = Absensi::selectRaw('tanggal, count(*) as total')
            ->where('status', 'Hadir')
            ->whereBetween('tanggal', [$today->copy()->subDays(6), $today])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();


        return view('dashboard', [
            'totalSiswa' => Siswa::count(),
            'totalGuru' => Guru::count(),
            'totalMapel' => MataPelajaran::count(),
            'totalKelas' => Kelas::count(),
            'absensiHariIni' => Absensi::where('tanggal', $today->toDateString())->where('status', 'Hadir')->count(),
            'absensiPerHari' => $absensiPerHari,
        ]);
    }

    private function dashboardGuru()
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if (!$guru) {
            abort(403);
        }

        $hari = Carbon::now()->locale('id')->isoFormat('dddd');

        $jadwalHariIni = Jadwal::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->where('hari', $hari)
            ->orderBy('jam_mulai')
            ->get();

        $kelasMapel = Pengajaran::with('mapel')
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.dashboard', [
            'jadwalHariIni' => $jadwalHariIni,
            'kelasMapel' => $kelasMapel,
        ]);
    }

    private function dashboardSiswa()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) {
            abort(403);
        }

        $hari = Carbon::now()->locale('id')->isoFormat('dddd');
        $kelas = Kelas::where('nama', $siswa->kelas)->first();

        if ($kelas) {
            $jadwalHariIni = Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->where('hari', $hari)
                ->orderBy('jam_mulai')
                ->get();
        } else {
            $jadwalHariIni = collect();
        }

        $absensiSummary = Absensi::where('siswa_id', $siswa->id)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $nilaiTerbaru = Penilaian::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->orderByDesc('id')
            ->take(5)
            ->get();

        $pengumuman = [];

        return view('siswa.dashboard', [
            'jadwalHariIni' => $jadwalHariIni,
            'absensiSummary' => $absensiSummary,
            'nilaiTerbaru' => $nilaiTerbaru,
            'pengumuman' => $pengumuman,
        ]);
    }
}
