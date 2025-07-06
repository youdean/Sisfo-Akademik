<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        // Grade 10 does not have majors
        foreach (['A', 'B', 'C'] as $suffix) {
            $data[] = ['nama' => '10' . $suffix];
        }

        // Grades 11 and 12 have IPA and IPS majors
        foreach ([11, 12] as $grade) {
            foreach (['IPA', 'IPS'] as $major) {
                foreach (['A', 'B'] as $suffix) {
                    $data[] = ['nama' => $grade . ' ' . $major . ' ' . $suffix];
                }
            }
        }

        DB::table('kelas')->insert($data);
    }
}
