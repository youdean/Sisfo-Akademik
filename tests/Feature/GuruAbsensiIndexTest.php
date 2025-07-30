<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\Pengajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruAbsensiIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_see_absensi_index_with_add_button(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '100',
            'nama' => 'Guru Test',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
        ]);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelas = Kelas::create([
            'nama' => 'X',
            'guru_id' => $guru->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        Pengajaran::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);

        $response = $this->actingAs($user)->get('/absensi');

        $response->assertRedirect('/absensi/pelajaran');
    }
}
