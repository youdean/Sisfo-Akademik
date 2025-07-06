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
            ['nama' => '10'],
            ['nama' => '11 IPA'],
            ['nama' => '11 IPS'],
            ['nama' => '12 IPA'],
            ['nama' => '12 IPS'],
        ];

        DB::table('kelas')->insert($data);
    }
}
