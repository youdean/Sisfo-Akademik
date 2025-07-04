<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::select('id', 'nama', 'kelas', 'tanggal_lahir')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Kelas',
            'Tanggal Lahir',
        ];
    }
}
