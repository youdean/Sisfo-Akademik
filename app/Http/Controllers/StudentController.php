<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Support\Facades\Auth;
use PDF;

class StudentController extends Controller
{
    /**
     * Display logged in student's profile.
     */
    public function profil()
    {
        $siswa = Siswa::where('nama', Auth::user()->name)->firstOrFail();
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show logged in student's attendance records.
     */
    public function absensi()
    {
        $siswa = Siswa::where('nama', Auth::user()->name)->firstOrFail();
        $absensi = $siswa->absensi()->get();
        return view('siswa.absensi', compact('siswa', 'absensi'));
    }

    /**
     * Show logged in student's grades.
     */
    public function nilai()
    {
        $siswa = Siswa::where('nama', Auth::user()->name)->firstOrFail();
        $nilai = Nilai::with('mapel')->where('siswa_id', $siswa->id)->get();
        return view('siswa.nilai', compact('siswa', 'nilai'));
    }

    /**
     * Download logged in student's report.
     */
    public function rapor()
    {
        $siswa = Siswa::where('nama', Auth::user()->name)->firstOrFail();
        $nilai = Nilai::with('mapel')->where('siswa_id', $siswa->id)->get();

        $pdf = PDF::loadView('rapor.pdf', [
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);

        return $pdf->download('rapor-'.$siswa->nama.'.pdf');
    }
}
