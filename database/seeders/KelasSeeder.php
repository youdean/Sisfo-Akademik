<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Only create the base classes without A/B/C subdivisions
        $data = [
            ['nama' => '10', 'guru_id' => 1],
            ['nama' => '11 IPA', 'guru_id' => 2],
            ['nama' => '11 IPS', 'guru_id' => 3],
            ['nama' => '12 IPA', 'guru_id' => 4],
            ['nama' => '12 IPS', 'guru_id' => 5],
        ];

        DB::table('kelas')->insert($data);
    }
}
