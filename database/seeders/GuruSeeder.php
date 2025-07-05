<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        $guruUser = User::where('email', 'guru@demo.com')->first();
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'nip' => str_pad($i, 18, '0', STR_PAD_LEFT),
                'nama' => $i === 1 ? 'Guru' : $faker->unique()->name,
                'tanggal_lahir' => $faker->dateTimeBetween('1980-01-01', '1995-12-31')->format('Y-m-d'),
                'user_id' => $i === 1 && $guruUser ? $guruUser->id : null,
            ];
        }
        DB::table('guru')->insert($data);
    }
}
