<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Only create the base classes without A/B/C subdivisions
        $taId = DB::table('tahun_ajaran')->value('id');
        $data = [
            ['nama' => 'X', 'guru_id' => 1, 'tahun_ajaran_id' => $taId],
            ['nama' => 'XI IPA', 'guru_id' => 2, 'tahun_ajaran_id' => $taId],
            ['nama' => 'XI IPS', 'guru_id' => 3, 'tahun_ajaran_id' => $taId],
            ['nama' => 'XII IPA', 'guru_id' => 4, 'tahun_ajaran_id' => $taId],
            ['nama' => 'XII IPS', 'guru_id' => 5, 'tahun_ajaran_id' => $taId],
        ];

        DB::table('kelas')->insert($data);
    }
}
