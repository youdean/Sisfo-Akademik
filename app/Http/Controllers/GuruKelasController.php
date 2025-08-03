<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Pengajaran;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class GuruKelasController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $kelasPengajaran = Pengajaran::where('guru_id', $guru->id)->pluck('kelas');
        $kelasWali = Kelas::where('guru_id', $guru->id)->pluck('nama');
        $kelasList = $kelasPengajaran->merge($kelasWali)->unique();
        $selected = request('kelas', $kelasList->first());
        $siswa = Siswa::where('kelas', $selected)->get();

        return view('guru.kelas', [
            'kelasList' => $kelasList,
            'siswa' => $siswa,
            'selected' => $selected,
        ]);
    }
}
