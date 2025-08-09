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
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentSelfCheckInTest extends TestCase
{
    use RefreshDatabase;

    private function setupData()
    {
        $guruUser = User::factory()->create(['role' => 'guru']);
        $siswaUser = User::factory()->create(['role' => 'siswa']);
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
        $siswa = Siswa::create([
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

        return [$guruUser, $siswaUser, $siswa, $jadwal, $mapel];
    }

    public function test_student_can_check_in_during_active_session(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal, $mapel] = $this->setupData();
        $password = 'secret';
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt($password),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->from('/saya/jadwal/'.$jadwal->id.'/absen')
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertRedirect('/saya/jadwal/'.$jadwal->id.'/absen');

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'status' => 'Hadir',
            'tanggal' => '2024-07-01',
        ]);
        $this->assertNotNull(Absensi::first()->check_in_at);
    }

    public function test_student_can_check_in_during_extended_schedule(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal, $mapel] = $this->setupData();
        $jadwal->update(['jam_selesai' => '09:00']);
        $password = 'secret';
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt($password),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->from('/saya/jadwal/'.$jadwal->id.'/absen')
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertRedirect('/saya/jadwal/'.$jadwal->id.'/absen');

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'status' => 'Hadir',
            'tanggal' => '2024-07-01',
        ]);
        $this->assertNotNull(Absensi::first()->check_in_at);
    }

    public function test_student_can_check_in_during_multi_hour_session(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal1, $mapel] = $this->setupData();
        $jadwal2 = Jadwal::create([
            'kelas_id' => $jadwal1->kelas_id,
            'mapel_id' => $mapel->id,
            'guru_id' => $jadwal1->guru_id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        $password = 'secret';
        AbsensiSession::create([
            'jadwal_id' => $jadwal1->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt($password),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->from('/saya/jadwal/'.$jadwal1->id.'/absen')
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertRedirect('/saya/jadwal/'.$jadwal1->id.'/absen');

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'status' => 'Hadir',
            'tanggal' => '2024-07-01',
        ]);
        $this->assertNotNull(Absensi::first()->check_in_at);
    }

    public function test_student_cannot_check_in_outside_time(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal, $mapel] = $this->setupData();
        $password = 'secret';
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt($password),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertForbidden();
    }

    public function test_student_cannot_check_in_twice(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal, $mapel] = $this->setupData();
        $password = 'secret';
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt($password),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->from('/saya/jadwal/'.$jadwal->id.'/absen')
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertRedirect('/saya/jadwal/'.$jadwal->id.'/absen');

        $this->actingAs($siswaUser)
            ->from('/saya/jadwal/'.$jadwal->id.'/absen')
            ->post('/saya/absensi/check-in', ['password' => $password])
            ->assertRedirect('/saya/jadwal/'.$jadwal->id.'/absen')
            ->assertSessionHasErrors('check_in');

        $this->assertEquals(1, Absensi::count());
    }

    public function test_student_cannot_check_in_with_wrong_password(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');
        [$guruUser, $siswaUser, $siswa, $jadwal, $mapel] = $this->setupData();
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => '2024-07-01',
            'opened_by' => $guruUser->id,
            'status_sesi' => 'open',
            'password' => bcrypt('secret'),
        ]);
        Absensi::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => '2024-07-01',
            'status' => 'Alpha',
        ]);

        $this->actingAs($siswaUser)
            ->post('/saya/absensi/check-in', ['password' => 'wrong'])
            ->assertForbidden();
    }
}
