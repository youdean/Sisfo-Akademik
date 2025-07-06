<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Pengajaran;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class InputNilaiController extends Controller
{
    private function guru()
    {
        return Guru::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $guru = $this->guru();
        $mapelList = Pengajaran::where('guru_id', $guru->id)
            ->with('mapel')
            ->get()
            ->pluck('mapel')
            ->unique('id');
        return view('input_nilai.index', ['mapelList' => $mapelList]);
    }

    public function kelas(MataPelajaran $mapel)
    {
        $guru = $this->guru();
        $kelasList = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->pluck('kelas')
            ->unique();
        return view('input_nilai.kelas', [
            'mapel' => $mapel,
            'kelasList' => $kelasList,
        ]);
    }

    public function nilai(MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $siswa = Siswa::where('kelas', $kelas)->get();
        $nilaiAbsensi = [];
        foreach ($siswa as $s) {
            $counts = Absensi::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $h = $counts['Hadir'] ?? 0;
            $i = $counts['Izin'] ?? 0;
            $sakit = $counts['Sakit'] ?? 0;
            $a = $counts['Alpha'] ?? 0;
            $total = $h + $i + $sakit + $a;
            $nilaiAbsensi[$s->id] = $total ? ($h / $total) * 100 : 0;
        }

        return view('input_nilai.nilai', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'siswa' => $siswa,
            'nilaiAbsensi' => $nilaiAbsensi,
        ]);
    }
}
