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
            AdminSeeder::class,
            UserSeeder::class,
            GuruSeeder::class,
            SiswaSeeder::class,
            TahunAjaranSeeder::class,
            MataPelajaranSeeder::class,
            AbsensiSeeder::class,
            KelasSeeder::class,
            PengajaranSeeder::class,
            JadwalSeeder::class,
        ]);
    }
}
