<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pengajaran')->insert([
            ['guru_id' => 1, 'mapel_id' => 1, 'kelas' => '10A'],
            ['guru_id' => 2, 'mapel_id' => 2, 'kelas' => '10B'],
        ]);
    }
}
