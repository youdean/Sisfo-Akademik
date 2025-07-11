<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
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
        $absensi = $siswa->absensi()->with('mapel')->get();
        return view('siswa.absensi', compact('siswa', 'absensi'));
    }

    /**
     * Form for student to record today's attendance.
     */
    public function absenForm()
    {
        $mapel = MataPelajaran::all();
        return view('siswa.absen', compact('mapel'));
    }

    /**
     * Store student's attendance for today.
     */
    public function absen(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $data = $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
        ]);

        Absensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'mapel_id' => $data['mapel_id'], 'tanggal' => date('Y-m-d')],
            ['status' => $data['status']]
        );

        return redirect()->route('student.absensi')->with('success', 'Absensi berhasil dicatat');
    }

}
