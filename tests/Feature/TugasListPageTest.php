<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TugasListPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_tugas_list_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = \App\Models\Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
        ]);
        $mapel = \App\Models\MataPelajaran::create(['nama' => 'Matematika']);
        \App\Models\Pengajaran::create(['guru_id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas' => '1A']);
        $siswa = \App\Models\Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '1',
            'kelas' => '1A',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);
        $penilaian = \App\Models\Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'semester' => 1,
        ]);
        \App\Models\NilaiTugas::create([
            'penilaian_id' => $penilaian->id,
            'nomor' => 1,
            'nilai' => 80,
        ]);

        $response = $this->actingAs($user)->get("/input-nilai/{$mapel->id}/1A/tugas-list");

        $response->assertOk();
        $response->assertSee('Tugas Nomor 1');
    }
}
