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
        $kelasList = [
            '10A', '10B', '10C',
            '11 IPA A', '11 IPA B', '11 IPS A', '11 IPS B',
            '12 IPA A', '12 IPA B', '12 IPS A', '12 IPS B',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama' => $i === 1 ? 'Siswa' : $faker->unique()->name,
                'nisn' => $faker->unique()->numerify('############'),
                'kelas' => $faker->randomElement($kelasList),
                'tempat_lahir' => $faker->city,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tanggal_lahir' => $faker->dateTimeBetween('2008-01-01', '2008-12-31')->format('Y-m-d'),
                'user_id' => $i === 1 && $siswaUser ? $siswaUser->id : null,
            ];
        }
        DB::table('siswa')->insert($data);
    }
}
