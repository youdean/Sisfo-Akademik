<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama' => 'Siswa ' . $i,
                'kelas' => '10' . chr(64 + ($i % 3) + 1),
                'tanggal_lahir' => '2008-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
            ];
        }
        DB::table('siswa')->insert($data);
    }
}
