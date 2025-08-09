<?php
namespace Tests\Feature;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Pengajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PtsPatSemesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_pts_pat_can_be_stored_and_listed_per_semester(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
        ]);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        Pengajaran::create(['guru_id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas' => '1A']);
        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '1',
            'nama_ortu' => 'Orang Tua',
            'kelas' => '1A',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);

        $this->actingAs($user)->post("/input-nilai/{$mapel->id}/1A/1/pts", [
            'pts' => [$siswa->id => 70],
        ])->assertRedirect('/input-nilai/' . $mapel->id . '/1A/1/pts');

        $this->actingAs($user)->post("/input-nilai/{$mapel->id}/1A/2/pat", [
            'pat' => [$siswa->id => 90],
        ])->assertRedirect('/input-nilai/' . $mapel->id . '/1A/2/pat');

        $responsePts = $this->actingAs($user)->get("/input-nilai/{$mapel->id}/1A/1/pts-nilai");
        $responsePts->assertOk();
        $responsePts->assertSee('70');

        $responsePat = $this->actingAs($user)->get("/input-nilai/{$mapel->id}/1A/2/pat-nilai");
        $responsePat->assertOk();
        $responsePat->assertSee('90');
    }
}
