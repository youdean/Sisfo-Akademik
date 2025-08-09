<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\AbsensiSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StartSessionTest extends TestCase
{
    use RefreshDatabase;

    private function setupData(): array
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $guruUser->id,
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
        $siswaUser = User::factory()->create(['role' => 'siswa']);
        Siswa::create([
            'nama' => 'Siswa 1',
            'nisn' => '123',
            'nama_ortu' => 'Orang Tua',
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $siswaUser->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        return [$guruUser, $jadwal];
    }

    public function test_teacher_can_start_session_midway(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');
        [$guruUser, $jadwal] = $this->setupData();

        $this->actingAs($guruUser)
            ->post(route('absensi.session.start', $jadwal->id))
            ->assertRedirect(route('absensi.session', $jadwal->id));

        $this->assertDatabaseHas('absensi_sessions', [
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'status_sesi' => 'open',
        ]);
    }

    public function test_teacher_can_restart_session_after_closing(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');
        [$guruUser, $jadwal] = $this->setupData();

        $this->actingAs($guruUser)->post(route('absensi.session.start', $jadwal->id));
        $this->actingAs($guruUser)->post(route('absensi.session.end', $jadwal->id));
        $this->actingAs($guruUser)->post(route('absensi.session.start', $jadwal->id));

        $response = $this->actingAs($guruUser)->get(route('absensi.session', $jadwal->id));

        $response->assertSee('Tutup Sesi');
    }

    public function test_second_consecutive_slot_cannot_start_session(): void
    {
        [$guruUser, $firstSchedule] = $this->setupData();
        $secondSchedule = Jadwal::create([
            'kelas_id' => $firstSchedule->kelas_id,
            'mapel_id' => $firstSchedule->mapel_id,
            'guru_id' => $firstSchedule->guru_id,
            'hari' => $firstSchedule->hari,
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        Carbon::setTestNow('2024-07-01 08:30:00');

        $this->actingAs($guruUser)
            ->post(route('absensi.session.start', $secondSchedule->id))
            ->assertForbidden();

        $this->assertDatabaseMissing('absensi_sessions', [
            'jadwal_id' => $secondSchedule->id,
            'tanggal' => '2024-07-01',
        ]);

        $response = $this->actingAs($guruUser)->get(route('absensi.session', $secondSchedule->id));
        $response->assertSee('<button class="btn btn-primary" disabled>Mulai Sesi</button>', false);
    }

}
