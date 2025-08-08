<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class RaporController extends Controller
{
    /**
     * Generate report card PDF.
     */
    public function cetak()
    {
        $rapor = [
            'identitas' => [
                'nama_sekolah' => 'SMA Contoh',
                'kelas' => 'X IPA 1',
                'alamat' => 'Jl. Pendidikan 1',
                'semester' => '1',
                'nama' => 'Budi',
                'tahun_ajaran' => '2023/2024',
                'nis' => '1234',
                'nisn' => '5678',
            ],
            'sikap' => [
                'spiritual' => 'Baik',
                'sosial' => 'Baik',
            ],
            'pengetahuan' => [
                [
                    'no' => 1,
                    'mapel' => 'Matematika',
                    'kkm' => 75,
                    'nilai' => 85,
                    'predikat' => 'A',
                    'deskripsi' => 'Sangat baik',
                ],
                [
                    'no' => 2,
                    'mapel' => 'Bahasa Indonesia',
                    'kkm' => 75,
                    'nilai' => 80,
                    'predikat' => 'B',
                    'deskripsi' => 'Baik',
                ],
            ],
            'ranking' => [
                'ke' => 1,
                'dari' => 30,
            ],
        ];

        $pdf = Pdf::loadView('rapor', compact('rapor'))->setPaper('a4', 'portrait');

        return $pdf->download('rapor.pdf');
    }
}

