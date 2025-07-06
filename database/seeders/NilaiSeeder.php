<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($s = 1; $s <= 50; $s++) {
            for ($m = 1; $m <= 10; $m++) {
                $data[] = [
                    'siswa_id' => $s,
                    'mapel_id' => $m,
                    'nilai' => rand(60, 100),
                    'semester' => rand(1, 2),
                ];
            }
        }
        DB::table('nilai')->insert($data);
    }
}
