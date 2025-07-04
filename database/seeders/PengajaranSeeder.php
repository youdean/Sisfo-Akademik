<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 30; $i++) {
            $data[] = [
                'guru_id' => ($i % 20) + 1,
                'mapel_id' => ($i % 10) + 1,
                'kelas' => '10' . chr(64 + ($i % 3) + 1),
            ];
        }
        DB::table('pengajaran')->insert($data);
    }
}
