<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            GuruSeeder::class,
            SiswaSeeder::class,
            MataPelajaranSeeder::class,
            NilaiSeeder::class,
            AbsensiSeeder::class,
            PengajaranSeeder::class,
            AdminSeeder::class,
            UserSeeder::class,
        ]);
    }
}
