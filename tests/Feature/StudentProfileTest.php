<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_profile_displays_email(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '1234567890',
            'kelas' => 'X',
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

