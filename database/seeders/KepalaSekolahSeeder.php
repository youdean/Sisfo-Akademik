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
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        DB::table('guru')->updateOrInsert(
            ['nuptk' => '1234567890123456'],
            [
                'nama' => 'Kepala Sekolah',
                'jabatan' => 'Kepala Sekolah',
                'email' => 'kepsek@demo.com',
                'tempat_lahir' => 'Jakarta',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1970-01-01',
                'user_id' => $user->id,
            ]
        );
    }
}
