<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
use PDF;

class RaporController extends Controller
{
    public function cetak(Request $request, Siswa $siswa)
    {
        $query = Nilai::with('mapel')->where('siswa_id', $siswa->id);
        if ($request->filled('semester')) {
            $query->where('semester', $request->input('semester'));
        }
        $nilai = $query->get();

        $pdf = PDF::loadView('rapor.pdf', [
            'siswa' => $siswa,
            'nilai' => $nilai
        ]);

        return $pdf->download('rapor-'.$siswa->nama.'.pdf');
    }
}
