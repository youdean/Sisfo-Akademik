<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nilai')->insert([
            ['siswa_id' => 1, 'mapel_id' => 1, 'nilai' => 85],
            ['siswa_id' => 1, 'mapel_id' => 2, 'nilai' => 90],
            ['siswa_id' => 2, 'mapel_id' => 1, 'nilai' => 88],
        ]);
    }
}
