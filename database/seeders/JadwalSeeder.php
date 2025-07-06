<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $kelas = DB::table('kelas')->get();
        $pengajaran = DB::table('pengajaran')->get()->keyBy(function ($row) {
            return $row->mapel_id.'-'.$row->kelas;
        });
        $data = [];
        foreach ($kelas as $kIndex => $k) {
            foreach ($hariList as $i => $hari) {
                $mapelId = (($kIndex + $i) % 10) + 1;
                $pengKey = $mapelId.'-'.$k->nama;
                $guruId = $pengajaran[$pengKey]->guru_id ?? 1;
                $start = sprintf('%02d:00', 7 + $i);
                $end = sprintf('%02d:00', 8 + $i);
                $data[] = [
                    'kelas_id' => $k->id,
                    'mapel_id' => $mapelId,
                    'guru_id' => $guruId,
                    'hari' => $hari,
                    'jam_mulai' => $start,
                    'jam_selesai' => $end,
                ];
            }
        }
        DB::table('jadwal')->insert($data);
    }
}
