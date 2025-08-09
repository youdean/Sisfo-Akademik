<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $guruUser = User::where('email', 'guru@demo.com')->first();

        $data = [
            [
                'nuptk' => '7544752654200033',
                'nama' => 'Bramsyah',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Samarinda',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1974-12-12',
                'user_id' => $guruUser?->id,
            ],
            [
                'nuptk' => '9450743647200002',
                'nama' => 'DRS MOCH ZAENAL AL AQILI',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Kepala Sekolah',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1965-01-18',
                'user_id' => null,
            ],
            [
                'nuptk' => '5738758660300032',
                'nama' => 'KOKOY ROKOYAH',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'BOGOR',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1980-06-04',
                'user_id' => null,
            ],
            [
                'nuptk' => '0255778679130033',
                'nama' => 'LUTHFIYANA FARIDH FADILLA',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-09-23',
                'user_id' => null,
            ],
            [
                'nuptk' => '5340744646200033',
                'nama' => 'Madropi',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'BOGOR',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1966-08-10',
                'user_id' => null,
            ],
            [
                'nuptk' => '8851776677230082',
                'nama' => 'Maya',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1998-05-19',
                'user_id' => null,
            ],
            [
                'nuptk' => '0046768669130083',
                'nama' => 'Nur Dwi Juliyanti',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Teluk Betung',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1990-07-14',
                'user_id' => null,
            ],
            [
                'nuptk' => '4836774675130142',
                'nama' => 'Nurmawaji',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'BOGOR',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1996-05-04',
                'user_id' => null,
            ],
            [
                'nuptk' => '2745152649170000',
                'nama' => 'Odri',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'TANJUNG AUR',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1979-10-16',
                'user_id' => null,
            ],
            [
                'nuptk' => '3038742644200043',
                'nama' => 'Samsurijal',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'BOGOR',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1964-07-06',
                'user_id' => null,
            ],
            [
                'nuptk' => '3863767668130102',
                'nama' => 'Siti Roayataeni',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'CIREBON',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1989-05-31',
                'user_id' => null,
            ],
            [
                'nuptk' => '9560780681230002',
                'nama' => 'SOFI MAENUR RIEVA',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'BOGOR',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2002-02-28',
                'user_id' => null,
            ],
            [
                'nuptk' => '0655750652200032',
                'nama' => 'Tutut Hariyadi',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1972-03-23',
                'user_id' => null,
            ],
            [
                'nuptk' => '8461763665300022',
                'nama' => 'Yanuria Sopiah',
                'email' => fake()->unique()->safeEmail(),
                'jabatan' => 'Staff',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1985-01-29',
                'user_id' => null,
            ],
        ];

        DB::table('guru')->insert($data);
    }
}
