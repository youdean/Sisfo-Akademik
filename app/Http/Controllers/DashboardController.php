<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Absensi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
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
}
