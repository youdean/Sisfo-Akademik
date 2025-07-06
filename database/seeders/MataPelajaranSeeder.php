<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mapel = [
            'Biologi',
            'Geografi',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Kimia',
            'Fisika',
            'Pendidikan Agama Islam dan Budi Pekerti',
            'Bahasa Indonesia',
            'Matematika',
            'Teknologi Informasi dan Komunikasi',
            'Seni Budaya',
            'Kemuhammadiyahan',
            'Bahasa Arab',
            'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'Ekonomi',
            'Sejarah',
            'Sosiologi',
            'Sejarah Indonesia',
            'Bahasa Inggris',
        ];
        $data = [];
        foreach ($mapel as $nama) {
            $data[] = ['nama' => $nama];
        }
        DB::table('mata_pelajaran')->insert($data);
    }
}
