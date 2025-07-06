<?php

namespace Tests\Feature;

use App\Models\Siswa;
use App\Models\MataPelajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiSemesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_nilai_can_be_created_for_multiple_semesters(): void
    {
        $siswa = Siswa::create([
            'nama' => 'Siswa Test',
            'nisn' => '0000000001',
            'kelas' => 'X',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);

        $mapel = MataPelajaran::create(['nama' => 'Matematika']);

        \App\Models\Nilai::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai' => 90,
            'semester' => 1,
        ]);

        \App\Models\Nilai::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'nilai' => 80,
            'semester' => 2,
        ]);

        $this->assertDatabaseHas('nilai', [
            'siswa_id' => $siswa->id,
            'semester' => 1,
            'nilai' => 90,
        ]);

        $this->assertDatabaseHas('nilai', [
            'siswa_id' => $siswa->id,
            'semester' => 2,
            'nilai' => 80,
        ]);

        $sem1 = \App\Models\Nilai::where('siswa_id', $siswa->id)->where('semester', 1)->get();
        $this->assertCount(1, $sem1);
    }
}

