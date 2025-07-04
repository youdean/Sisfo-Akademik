<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                // Gunakan nama "Guru" untuk id pertama agar sesuai dengan user default
                'nip' => str_pad($i, 18, '0', STR_PAD_LEFT),
                'nama' => $i === 1 ? 'Guru' : $faker->unique()->name,
            ];
        }
        DB::table('guru')->insert($data);
    }
}
