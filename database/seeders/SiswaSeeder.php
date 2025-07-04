<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('siswa')->insert([
            ['nama' => 'Andi', 'kelas' => '10A', 'tanggal_lahir' => '2008-01-10'],
            ['nama' => 'Siti', 'kelas' => '10B', 'tanggal_lahir' => '2008-02-15'],
        ]);
    }
}
