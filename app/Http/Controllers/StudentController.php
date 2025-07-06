<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display logged in student's profile.
     */
    public function profil()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        return view('siswa.show', compact('siswa'));
    }

    /**
     * Show logged in student's attendance records.
     */
    public function absensi()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $absensi = $siswa->absensi()->get();
        return view('siswa.absensi', compact('siswa', 'absensi'));
    }

    /**
     * Form for student to record today's attendance.
     */
    public function absenForm()
    {
        return view('siswa.absen');
    }

    /**
     * Store student's attendance for today.
     */
    public function absen(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $data = $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
        ]);

        Absensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'tanggal' => date('Y-m-d')],
            ['status' => $data['status']]
        );

        return redirect()->route('student.absensi')->with('success', 'Absensi berhasil dicatat');
    }

    /**
     * Show logged in student's grades.
     */
    public function nilai(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $query = Nilai::with('mapel')->where('siswa_id', $siswa->id);
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }
        $nilai = $query->get();
        return view('siswa.nilai', compact('siswa', 'nilai'));
    }

    /**
     * Download logged in student's report.
     */
    public function rapor(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $query = Nilai::with('mapel')->where('siswa_id', $siswa->id);
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }
        $nilai = $query->get();

        $pdf = PDF::loadView('rapor.pdf', [
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);

        return $pdf->download('rapor-'.$siswa->nama.'.pdf');
    }
}
