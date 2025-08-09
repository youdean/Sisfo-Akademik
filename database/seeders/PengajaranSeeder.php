<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengajaranSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nuptk' => '7544752654200033', 'mapel' => 'Biologi', 'kelas' => ['X', 'XI IPA', 'XII IPA']],
            ['nuptk' => '5738758660300032', 'mapel' => 'Geografi', 'kelas' => ['X', 'XI IPS', 'XII IPS']],
            ['nuptk' => '5738758660300032', 'mapel' => 'Pendidikan Pancasila dan Kewarganegaraan', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '0255778679130033', 'mapel' => 'Kimia', 'kelas' => ['X', 'XI IPA', 'XII IPA']],
            ['nuptk' => '0255778679130033', 'mapel' => 'Fisika', 'kelas' => ['X', 'XI IPA', 'XII IPA']],
            ['nuptk' => '5340744646200033', 'mapel' => 'Pendidikan Agama Islam dan Budi Pekerti', 'kelas' => ['XII IPA', 'XII IPS']],
            ['nuptk' => '8851776677230082', 'mapel' => 'Bahasa Indonesia', 'kelas' => ['XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '0046768669130083', 'mapel' => 'Matematika', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '0046768669130083', 'mapel' => 'Teknologi Informasi dan Komunikasi', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '4836774675130142', 'mapel' => 'Seni Budaya', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '4836774675130142', 'mapel' => 'Kemuhammadiyahan', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '2745152649170000', 'mapel' => 'Pendidikan Agama Islam dan Budi Pekerti', 'kelas' => ['X', 'XI IPA', 'XI IPS']],
            ['nuptk' => '2745152649170000', 'mapel' => 'Bahasa Arab', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '3038742644200043', 'mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '3863767668130102', 'mapel' => 'Ekonomi', 'kelas' => ['X', 'XI IPS', 'XII IPS']],
            ['nuptk' => '3863767668130102', 'mapel' => 'Sejarah', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
            ['nuptk' => '9560780681230002', 'mapel' => 'Sosiologi', 'kelas' => ['X', 'XI IPS', 'XII IPS']],
            ['nuptk' => '0655750652200032', 'mapel' => 'Bahasa Indonesia', 'kelas' => ['X']],
            ['nuptk' => '8461763665300022', 'mapel' => 'Sejarah Indonesia', 'kelas' => ['X', 'XI IPS', 'XII IPS']],
            ['nuptk' => '8461763665300022', 'mapel' => 'Bahasa Inggris', 'kelas' => ['X', 'XI IPA', 'XI IPS', 'XII IPA', 'XII IPS']],
        ];

        foreach ($data as $row) {
            $guruId = DB::table('guru')->where('nuptk', $row['nuptk'])->value('id');
            $mapelId = DB::table('mata_pelajaran')->where('nama', $row['mapel'])->value('id');
            foreach ($row['kelas'] as $kelas) {
                DB::table('pengajaran')->insert([
                    'guru_id' => $guruId,
                    'mapel_id' => $mapelId,
                    'kelas' => $kelas,
                ]);
            }
        }
    }
}
