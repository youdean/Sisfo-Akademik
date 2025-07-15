<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_profile_displays_email(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '1234567890',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get('/saya');
        $response->assertOk();
        $response->assertSee($user->email);
    }
}

