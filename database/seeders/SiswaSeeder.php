<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama' => $faker->unique()->name,
                'kelas' => '10' . chr(64 + ($i % 3) + 1),
                'tanggal_lahir' => $faker->dateTimeBetween('2008-01-01', '2008-12-31')->format('Y-m-d'),
            ];
        }
        DB::table('siswa')->insert($data);
    }
}
