<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'nip' => str_pad($i, 18, '0', STR_PAD_LEFT),
                'nama' => 'Guru ' . $i,
            ];
        }
        DB::table('guru')->insert($data);
    }
}
