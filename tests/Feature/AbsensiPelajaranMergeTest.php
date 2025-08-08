<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiPelajaranMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_consecutive_slots_are_merged_in_attendance_menu(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');

        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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

        $first = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);
        $second = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        $response = $this->actingAs($user)->get('/absensi/pelajaran');

        $response->assertOk();
        $response->assertSee('07:00 - 09:00');
        $response->assertSee(route('absensi.pelajaran.form', ['jadwal' => $first->id, 'tanggal' => '2024-07-01']));
        $response->assertDontSee(route('absensi.pelajaran.form', ['jadwal' => $second->id, 'tanggal' => '2024-07-01']));
    }
}
