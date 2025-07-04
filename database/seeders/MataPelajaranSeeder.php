<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Kimia',
            'Fisika',
            'Biologi',
            'Ekonomi',
            'Sejarah',
            'Geografi',
            'Seni Budaya',
        ];
        $data = [];
        foreach ($mapel as $nama) {
            $data[] = ['nama' => $nama];
        }
        DB::table('mata_pelajaran')->insert($data);
    }
}
