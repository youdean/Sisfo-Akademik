<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        $kelasList = [
            'X',
            'XI IPA', 'XI IPS',
            'XII IPA', 'XII IPS',
        ];
        $index = 0;
        for ($mapel = 1; $mapel <= 10; $mapel++) {
            foreach ($kelasList as $kelas) {
                $data[] = [
                    'guru_id' => ($index % 20) + 1,
                    'mapel_id' => $mapel,
                    'kelas' => $kelas,
                ];
                $index++;
            }
        }
        DB::table('pengajaran')->insert($data);
    }
}
