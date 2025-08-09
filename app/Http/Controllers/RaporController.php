<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Penilaian;

class RaporController extends Controller
{
    /**
     * Generate report card PDF using database values.
     */
    public function cetak(Request $request)
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $semester = (int) $request->query('semester', 1);

        $penilaian = Penilaian::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->where('semester', $semester)
            ->get();

        $pdf = Pdf::loadView('rapor', [
            'siswa' => $siswa,
            'penilaian' => $penilaian,
            'semester' => $semester,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rapor.pdf');
    }
}
