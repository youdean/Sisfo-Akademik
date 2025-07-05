<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        foreach ([10,11,12] as $grade) {
            foreach (['A','B','C'] as $suffix) {
                $data[] = ['nama' => $grade . $suffix];
            }
        }
        DB::table('kelas')->insert($data);
    }
}
