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
        $tahunAjaranId = DB::table('tahun_ajaran')->value('id');
        $kelasList = [
            'X',
            'XI IPA', 'XI IPS',
            'XII IPA', 'XII IPS',
        ];

        for ($i = 1; $i <= 50; $i++) {
            $data[] = [
                'nama' => $i === 1 
                    ? 'Siswa' 
                    : $faker->unique()->firstName . ' ' . $faker->lastName,
                'nisn' => $faker->unique()->numerify('############'),
                'nama_ortu' => $faker->firstName . ' ' . $faker->lastName,
                'kelas' => $faker->randomElement($kelasList),
                'tempat_lahir' => $faker->city,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'tanggal_lahir' => $faker->dateTimeBetween('2008-01-01', '2008-12-31')->format('Y-m-d'),
                'user_id' => $i === 1 && $siswaUser ? $siswaUser->id : null,
                'tahun_ajaran_id' => $tahunAjaranId,
            ];
        }
        DB::table('siswa')->insert($data);
    }
}
