<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::with('tahunAjaran')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'nama' => $s->nama,
                'nisn' => $s->nisn,
                'kelas' => $s->kelas,
                'tahun_ajaran' => $s->tahunAjaran?->nama,
                'tempat_lahir' => $s->tempat_lahir,
                'jenis_kelamin' => $s->jenis_kelamin,
                'tanggal_lahir' => $s->tanggal_lahir,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NISN',
            'Kelas',
            'Tahun Ajaran',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Tanggal Lahir',
        ];
    }
}
