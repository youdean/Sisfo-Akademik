<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_statistics(): void
    {
        $user = User::factory()->create();

        Siswa::create([
            'nama' => 'Test1',
            'nisn' => '0000000001',
            'kelas' => 'X',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01'
        ]);
        Siswa::create([
            'nama' => 'Test2',
            'nisn' => '0000000002',
            'kelas' => 'XI IPA',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2000-01-02'
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Total Kelas');
    }
}
