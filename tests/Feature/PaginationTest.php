<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_siswa_index_displays_pagination_links(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        for ($i = 1; $i <= 11; $i++) {
            Siswa::create([
                'nama' => 'Siswa '.$i,
                'nisn' => str_pad((string)$i, 10, '0', STR_PAD_LEFT),
                'kelas' => 'X',
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
