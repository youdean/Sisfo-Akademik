<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::select('id', 'nama', 'nisn', 'kelas', 'tempat_lahir', 'jenis_kelamin', 'tanggal_lahir')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NISN',
            'Kelas',
            'Tempat Lahir',
            'Jenis Kelamin',
            'Tanggal Lahir',
        ];
    }
}
