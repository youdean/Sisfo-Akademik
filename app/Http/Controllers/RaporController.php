<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Penilaian;

class RaporController extends Controller
{
    /**
     * Generate report card PDF using database values.
     */
    public function cetak()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $penilaian = Penilaian::with('mapel')
            ->where('siswa_id', $siswa->id)
            ->get();

        $pdf = Pdf::loadView('rapor', [
            'siswa' => $siswa,
            'penilaian' => $penilaian,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('rapor.pdf');
    }
}
