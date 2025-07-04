<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Guru',
                'email' => 'guru@demo.com',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ],
            [
                'name' => 'Siswa',
                'email' => 'siswa@demo.com',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
