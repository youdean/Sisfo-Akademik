<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = ['Hadir', 'Izin', 'Sakit', 'Alpha'];
        $data = [];
        for ($s = 1; $s <= 50; $s++) {
            for ($d = 1; $d <= 5; $d++) {
                $data[] = [
                    'siswa_id' => $s,
                    'tanggal' => date('Y-m-d', strtotime("2025-07-" . str_pad($d, 2, '0', STR_PAD_LEFT))),
                    'status' => $statuses[array_rand($statuses)],
                ];
            }
        }
        DB::table('absensi')->insert($data);
    }
}
