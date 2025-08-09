<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class KepalaSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'kepsek@demo.com'],
            [
                'name' => 'DRS MOCH ZAENAL AL AQILI',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        DB::table('guru')->updateOrInsert(
            ['nuptk' => '9450743647200002'],
            [
                'nama' => 'DRS MOCH ZAENAL AL AQILI',
                'jabatan' => 'Kepala Sekolah',
                'email' => 'kepsek@demo.com',
                'tempat_lahir' => 'Bogor',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1965-01-18',
                'user_id' => $user->id,
            ]
        );
    }
}
