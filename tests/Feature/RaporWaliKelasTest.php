<?php

namespace Tests\Feature;

use App\Models\{User,Guru,Siswa,Kelas,TahunAjaran,MataPelajaran,Penilaian};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaporWaliKelasTest extends TestCase
{
    use RefreshDatabase;

    public function test_wali_kelas_can_print_rapor(): void
    {
        Pdf::shouldReceive('loadView')->andReturnSelf();
        Pdf::shouldReceive('setPaper')->andReturnSelf();
        Pdf::shouldReceive('download')->andReturn(response('PDF', 200, ['Content-Type' => 'application/pdf']));

        $waliUser = User::factory()->create(['role' => 'guru']);
        $wali = Guru::create([
            'nuptk' => '1',
            'nama' => 'Wali',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $waliUser->id,
        ]);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        Kelas::create([
            'nama' => 'X',
            'guru_id' => $wali->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        $studentUser = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '123',
            'nama_ortu' => 'Ortu',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $studentUser->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'semester' => 1,
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'pts' => 0,
            'pat' => 0,
        ]);

        $response = $this->actingAs($waliUser)->get('/rapor/cetak/' . $siswa->id);

        $response->assertOk();
    }

    public function test_non_wali_kelas_cannot_print_rapor(): void
    {
        $waliUser = User::factory()->create(['role' => 'guru']);
        $wali = Guru::create([
            'nuptk' => '1',
            'nama' => 'Wali',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $waliUser->id,
        ]);
        $otherUser = User::factory()->create(['role' => 'guru']);
        Guru::create([
            'nuptk' => '2',
            'nama' => 'Guru Lain',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $otherUser->id,
        ]);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        Kelas::create([
            'nama' => 'X',
            'guru_id' => $wali->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        $studentUser = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '123',
            'nama_ortu' => 'Ortu',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $studentUser->id,
        ]);

        $response = $this->actingAs($otherUser)->get('/rapor/cetak/' . $siswa->id);

        $response->assertStatus(403);
    }
}

