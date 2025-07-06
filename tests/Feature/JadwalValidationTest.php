<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_end_time_must_be_after_start_time(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '999',
            'nama' => 'Guru Test',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $kelas = Kelas::create(['nama' => '10']);

        $response = $this->actingAs($user)
            ->from('/jadwal/create')
            ->post('/jadwal', [
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guru->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '07:00',
            ]);

        $response->assertRedirect('/jadwal/create');
        $response->assertSessionHasErrors('jam_selesai');
        $this->assertEquals(0, Jadwal::count());
    }
}
