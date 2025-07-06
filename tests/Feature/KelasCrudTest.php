<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_kelas_index_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        for ($i = 1; $i <= 11; $i++) {
            $wali = \App\Models\Guru::create([
                'nuptk' => '900'.$i,
                'nama' => 'Wali '.$i,
                'tempat_lahir' => 'Kota',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
            ]);
            \App\Models\Kelas::create(['nama' => 'K'.$i, 'guru_id' => $wali->id, 'tahun_ajaran_id' => $ta->id]);
        }

        $response = $this->actingAs($user)->get('/kelas');

        $response->assertOk();
        $response->assertSee('Daftar Kelas');
        $response->assertSee('?page=2');
    }
}
