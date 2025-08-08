<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
     * Show logged in student's scores.
     */
    public function nilai()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $penilaian = Penilaian::with('mapel', 'tugas')
            ->where('siswa_id', $siswa->id)
            ->get();

        return view('siswa.nilai', compact('siswa', 'penilaian'));
    }


    /**
     * Display schedule for the logged in student.
     */
    public function jadwal()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('nama', $siswa->kelas)->first();

        if ($kelas) {
            $jadwal = Jadwal::with(['mapel', 'guru'])
                ->where('kelas_id', $kelas->id)
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari')
                ->map(fn ($items) => Jadwal::mergeConsecutive($items));
        } else {
            $jadwal = collect();
        }

        return view('siswa.jadwal', [
            'siswa' => $siswa,
            'jadwal' => $jadwal,
        ]);
    }

    /**
     * Form for taking attendance from schedule.
     */
    public function jadwalAbsenForm(Jadwal $jadwal)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('nama', $siswa->kelas)->first();
        if (!$kelas || $jadwal->kelas_id !== $kelas->id) {
            abort(403);
        }

        $now = Carbon::now();
        $hari = $now->locale('id')->isoFormat('dddd');
        $time = $now->format('H:i');

        if ($hari !== $jadwal->hari || $time < $jadwal->jam_mulai || $time > $jadwal->jam_selesai) {
            abort(403);
        }

        $riwayat = Absensi::where('siswa_id', $siswa->id)
            ->where('mapel_id', $jadwal->mapel_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('siswa.absen_jadwal', compact('jadwal', 'riwayat'));
    }

    /**
     * Store attendance from schedule.
     */
    public function jadwalAbsen(Request $request, Jadwal $jadwal)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();
        $kelas = Kelas::where('nama', $siswa->kelas)->first();
        if (!$kelas || $jadwal->kelas_id !== $kelas->id) {
            abort(403);
        }

        $now = Carbon::now();
        $hari = $now->locale('id')->isoFormat('dddd');
        $time = $now->format('H:i');

        if ($hari !== $jadwal->hari || $time < $jadwal->jam_mulai || $time > $jadwal->jam_selesai) {
            abort(403);
        }

        $data = $request->validate([
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
        ]);

        Absensi::updateOrCreate(
            ['siswa_id' => $siswa->id, 'mapel_id' => $jadwal->mapel_id, 'tanggal' => date('Y-m-d')],
            ['status' => $data['status']]
        );

        return redirect()->route('student.jadwal')->with('success', 'Absensi berhasil dicatat');
    }

}
