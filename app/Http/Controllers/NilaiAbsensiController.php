<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Pengajaran;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class NilaiAbsensiController extends Controller
{
    private function kelasGuru(): array
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return Kelas::pluck('nama')->toArray();
        }

        $guru = Guru::where('user_id', $user?->id)->first();
        if (!$guru) {
            return [];
        }

        return Pengajaran::where('guru_id', $guru->id)->pluck('kelas')->toArray();
    }

    public function index(Request $request)
    {
        $kelas = $request->input('kelas');
        $siswaQuery = Siswa::whereIn('kelas', $this->kelasGuru());
        if ($kelas) {
            $siswaQuery->where('kelas', $kelas);
        }

        $rekap = $siswaQuery->withCount([
            'absensi as hadir' => function ($q) {
                $q->where('status', 'Hadir');
            },
            'absensi as izin' => function ($q) {
                $q->where('status', 'Izin');
            },
            'absensi as sakit' => function ($q) {
                $q->where('status', 'Sakit');
            },
            'absensi as alpha' => function ($q) {
                $q->where('status', 'Alpha');
            },
        ])->get();

        return view('nilai_absensi.index', [
            'rekap' => $rekap,
            'kelas' => $kelas,
            'kelasList' => $this->kelasGuru(),
        ]);
    }
}
