<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasTahunAjaranValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tahun_ajaran_id_is_required_and_must_exist(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '7001',
            'nama' => 'Wali',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);

        $response = $this->actingAs($user)
            ->from('/kelas/create')
            ->post('/kelas', [
                'nama' => 'X',
                'guru_id' => $guru->id,
                'tahun_ajaran_id' => 999,
            ]);
        $response->assertRedirect('/kelas/create');
        $response->assertSessionHasErrors('tahun_ajaran_id');
        $this->assertEquals(0, Kelas::count());

        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        $response = $this->actingAs($user)
            ->post('/kelas', [
                'nama' => 'X',
                'guru_id' => $guru->id,
                'tahun_ajaran_id' => $ta->id,
            ]);
        $response->assertRedirect('/kelas');
        $this->assertEquals(1, Kelas::count());
    }
}
