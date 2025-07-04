<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('absensi')->insert([
            ['siswa_id' => 1, 'tanggal' => '2025-07-01', 'status' => 'Hadir'],
            ['siswa_id' => 2, 'tanggal' => '2025-07-01', 'status' => 'Izin'],
            ['siswa_id' => 1, 'tanggal' => '2025-07-02', 'status' => 'Hadir'],
        ]);
    }
}
