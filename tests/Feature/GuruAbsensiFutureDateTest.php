<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jadwal;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruAbsensiFutureDateTest extends TestCase
{
    use RefreshDatabase;

    private function setupData()
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $user->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelas = Kelas::create([
            'nama' => 'X',
            'guru_id' => $guru->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        $siswa = Siswa::create([
            'nama' => 'Siswa 1',
            'nisn' => '123',
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        return [$user, $siswa, $jadwal];
    }

    public function test_guru_cannot_store_absensi_for_future_date(): void
    {
        Carbon::setTestNow('2024-07-01 08:00:00');

        [$user, $siswa, $jadwal] = $this->setupData();

        $this->actingAs($user)
            ->post('/absensi/pelajaran/'.$jadwal->id, [
                'tanggal' => '2024-07-02',
                'status' => [$siswa->id => 'Hadir'],
            ])
            ->assertSessionHasErrors('tanggal');

        $this->assertDatabaseMissing('absensi', [
            'siswa_id' => $siswa->id,
            'tanggal' => '2024-07-02',
        ]);
    }

    public function test_future_date_form_shows_disabled_button(): void
    {
        Carbon::setTestNow('2024-07-01 08:00:00');

        [$user, $siswa, $jadwal] = $this->setupData();

        $response = $this->actingAs($user)
            ->get('/absensi/pelajaran/'.$jadwal->id.'?tanggal=2024-07-02')
            ->assertStatus(200);

        $response->assertSee('<button class="btn btn-success"  disabled', false);
    }
}
