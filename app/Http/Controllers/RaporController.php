<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
use PDF;

class RaporController extends Controller
{
    public function cetak(Siswa $siswa)
    {
        $nilai = Nilai::with('mapel')->where('siswa_id', $siswa->id)->get();

        $pdf = PDF::loadView('rapor.pdf', [
            'siswa' => $siswa,
            'nilai' => $nilai
        ]);

        return $pdf->download('rapor-'.$siswa->nama.'.pdf');
    }
}
