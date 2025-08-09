<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Penilaian;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Guru;

class RaporController extends Controller
{
    /**
     * Generate report card PDF using database values.
     */
    public function cetak(Request $request, ?Siswa $siswa = null)
    {
        $role = Auth::user()->role;

        if (in_array($role, ['admin', 'guru'])) {
            abort_unless($siswa, 404);
        } else {
            $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        }

        $semester = (int) $request->query('semester', 1);

        $penilaian = Penilaian::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->get();

        $ketidakhadiran = Absensi::where('siswa_id', $siswa->id)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $kelas = Kelas::with('waliKelas')
            ->where('nama', $siswa->kelas)
            ->where('tahun_ajaran_id', $siswa->tahun_ajaran_id)
            ->first();

        if ($role === 'guru') {
            $guruId = Guru::where('user_id', Auth::id())->value('id');
            abort_unless($kelas && $kelas->guru_id === $guruId, 403);
        }

        $waliKelas = $kelas?->waliKelas;

        $kepalaSekolah = Guru::where('jabatan', 'Kepala Sekolah')->first();

        $pdf = Pdf::loadView('rapor', [
            'siswa' => $siswa,
            'penilaian' => $penilaian,
            'semester' => $semester,
            'ketidakhadiran' => $ketidakhadiran,
            'waliKelas' => $waliKelas->nama ?? '',
            'waliKelasNuptk' => $waliKelas->nuptk ?? '',
            'kepalaSekolah' => $kepalaSekolah,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rapor.pdf');
    }
}
