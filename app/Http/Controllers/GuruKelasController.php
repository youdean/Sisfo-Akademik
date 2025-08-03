<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Pengajaran;
use App\Models\Siswa;
use App\Models\Penilaian;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class GuruKelasController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelasList = Pengajaran::where('guru_id', $guru->id)->pluck('kelas')->unique();
        $selected = request('kelas', $kelasList->first());
        $siswa = Siswa::where('kelas', $selected)->get();

        return view('guru.kelas', [
            'kelasList' => $kelasList,
            'siswa' => $siswa,
            'selected' => $selected,
        ]);
    }

    public function nilaiKelas()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('guru_id', $guru->id)->firstOrFail();
        $siswa = Siswa::where('kelas', $kelas->nama)->get();
        $mapel = MataPelajaran::all();
        $penilaian = Penilaian::whereIn('siswa_id', $siswa->pluck('id'))
            ->get()
            ->groupBy(['siswa_id', 'mapel_id']);

        return view('guru.nilai_kelas', [
            'kelas' => $kelas,
            'siswa' => $siswa,
            'mapel' => $mapel,
            'penilaian' => $penilaian,
        ]);
    }
}
