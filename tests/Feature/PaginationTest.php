<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_index_displays_pagination_links(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        for ($i = 1; $i <= 11; $i++) {
            Siswa::create([
                'nama' => 'Siswa '.$i,
                'nisn' => str_pad((string)$i, 10, '0', STR_PAD_LEFT),
                'kelas' => 'X',
                'tahun_ajaran_id' => $ta->id,
                'tempat_lahir' => 'Test',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-01-01',
            ]);
        }

        $response = $this->actingAs($user)->get('/siswa');
        $response->assertOk();
        $response->assertSee('?page=2');
    }
}
