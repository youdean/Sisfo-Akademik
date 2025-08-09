<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use App\Models\NilaiTugas;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentScoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_view_scores(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '123',
            'nama_ortu' => 'Orang Tua',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        $penilaian = Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'pts' => 80,
            'pat' => 90,
        ]);
        NilaiTugas::create([
            'penilaian_id' => $penilaian->id,
            'nama' => 'Tugas 1',
            'nilai' => 85,
        ]);

        $response = $this->actingAs($user)->get('/saya/nilai');

        $response->assertOk();
        $response->assertSee('Nilai Saya');
        $response->assertSee('IPA');
        $response->assertSee('80');
        $response->assertSee('90');
    }
}
