<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_statistics(): void
    {
        $user = User::factory()->create();

        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        Siswa::create([
            'nama' => 'Test1',
            'nisn' => '0000000001',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01'
        ]);
        Siswa::create([
            'nama' => 'Test2',
            'nisn' => '0000000002',
            'kelas' => 'XI IPA',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2000-01-02'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Total Kelas');
    }
}
