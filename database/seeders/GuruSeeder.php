<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guru')->insert([
            ['nip' => '198001012005011001', 'nama' => 'Pak Budi'],
            ['nip' => '197512312001011002', 'nama' => 'Bu Ani'],
        ]);
    }
}
