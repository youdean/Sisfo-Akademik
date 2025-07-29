<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Pengajaran;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruWeeklyScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_weekly_schedule_grouped_by_day(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '111',
            'nama' => 'Guru 1',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
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

        Pengajaran::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);

        Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);
        Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Selasa',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        $response = $this->actingAs($user)->get('/absensi/pelajaran');

        $response->assertOk();
        $response->assertSee('Jadwal Minggu Ini');
        $response->assertSee('Senin');
        $response->assertSee('Selasa');
        $response->assertSee($mapel->nama);
    }
}
