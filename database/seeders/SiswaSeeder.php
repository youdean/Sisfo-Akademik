<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        $siswaUser = User::where('email', 'siswa@demo.com')->first();
        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama' => $i === 1 ? 'Siswa' : $faker->unique()->name,
                'nisn' => $faker->unique()->numerify('############'),
                'kelas' => '10' . chr(64 + ($i % 3) + 1),
                'tempat_lahir' => $faker->city,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tanggal_lahir' => $faker->dateTimeBetween('2008-01-01', '2008-12-31')->format('Y-m-d'),
                'user_id' => $i === 1 && $siswaUser ? $siswaUser->id : null,
            ];
        }
        DB::table('siswa')->insert($data);
    }
}
