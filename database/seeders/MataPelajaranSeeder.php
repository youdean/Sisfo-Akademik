<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_pelajaran')->insert([
            ['nama' => 'Matematika'],
            ['nama' => 'Bahasa Indonesia'],
            ['nama' => 'Fisika'],
        ]);
    }
}
