<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;
    protected $kelas;

    public function __construct($bulan, $tahun, $kelas = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->kelas = $kelas;
    }

    public function collection()
    {
        $siswaQuery = Siswa::query();
        if ($this->kelas) {
            $siswaQuery->where('kelas', $this->kelas);
        }

        $rekap = $siswaQuery->withCount([
            'absensi as hadir' => function ($q) {
                $q->where('status', 'Hadir')
                  ->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
            },
            'absensi as izin' => function ($q) {
                $q->where('status', 'Izin')
                  ->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
            },
            'absensi as sakit' => function ($q) {
                $q->where('status', 'Sakit')
                  ->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
            },
            'absensi as alpha' => function ($q) {
                $q->where('status', 'Alpha')
                  ->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
            },
        ])->get();

        return $rekap->map(function ($s) {
            return [
                'Nama' => $s->nama,
                'Kelas' => $s->kelas,
                'Hadir' => $s->hadir,
                'Izin' => $s->izin,
                'Sakit' => $s->sakit,
                'Alpha' => $s->alpha,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'Kelas', 'Hadir', 'Izin', 'Sakit', 'Alpha'];
    }
}
