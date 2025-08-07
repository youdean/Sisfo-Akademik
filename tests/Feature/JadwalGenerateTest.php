<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Pengajaran;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalGenerateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_schedule_with_four_hours_per_subject_and_no_teacher_conflict(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = Guru::create([
            'nuptk' => '500',
            'nama' => 'Guru Generate',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $waliA = Guru::create([
            'nuptk' => '501',
            'nama' => 'Wali A',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $waliB = Guru::create([
            'nuptk' => '502',
            'nama' => 'Wali B',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelasA = Kelas::create(['nama' => 'A', 'guru_id' => $waliA->id, 'tahun_ajaran_id' => $ta->id]);
        $kelasB = Kelas::create(['nama' => 'B', 'guru_id' => $waliB->id, 'tahun_ajaran_id' => $ta->id]);

        Pengajaran::create(['guru_id' => $teacher->id, 'mapel_id' => $mapel->id, 'kelas' => $kelasA->nama]);
        Pengajaran::create(['guru_id' => $teacher->id, 'mapel_id' => $mapel->id, 'kelas' => $kelasB->nama]);

        $this->actingAs($admin)->post('/jadwal/generate')->assertRedirect('/jadwal');

        $this->assertEquals(8, Jadwal::count());
        $this->assertEquals(4, Jadwal::where('kelas_id', $kelasA->id)->where('mapel_id', $mapel->id)->count());
        $this->assertEquals(4, Jadwal::where('kelas_id', $kelasB->id)->where('mapel_id', $mapel->id)->count());

        $times = Jadwal::where('guru_id', $teacher->id)->get()->map(fn($j) => $j->hari . ' ' . $j->jam_mulai);
        $this->assertEquals($times->count(), $times->unique()->count());

        foreach ([$kelasA, $kelasB] as $kelas) {
            $schedule = Jadwal::where('kelas_id', $kelas->id)
                ->where('mapel_id', $mapel->id)
                ->get();
            $grouped = $schedule->groupBy('hari');

            foreach ($grouped as $entries) {
                $this->assertLessThanOrEqual(2, $entries->count());
                if ($entries->count() === 2) {
                    $sorted = $entries->sortBy('jam_mulai')->values();
                    $this->assertEquals(
                        date('H:i', strtotime($sorted[0]->jam_mulai . ' +1 hour')),
                        $sorted[1]->jam_mulai
                    );
                }
            }

            $this->assertGreaterThanOrEqual(2, $grouped->count());
        }
    }

    public function test_non_admin_cannot_access_generate_schedule_endpoint(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $this->actingAs($user)->post('/jadwal/generate')->assertStatus(403);
    }

    public function test_generate_schedule_for_all_classes_without_slot_errors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $teacherA = Guru::create([
            'nuptk' => '600',
            'nama' => 'Guru A',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $teacherB = Guru::create([
            'nuptk' => '601',
            'nama' => 'Guru B',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1986-01-01',
        ]);

        $mapel1 = MataPelajaran::create(['nama' => 'Matematika']);
        $mapel2 = MataPelajaran::create(['nama' => 'Bahasa']);

        $waliA = Guru::create([
            'nuptk' => '602',
            'nama' => 'Wali A',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $waliB = Guru::create([
            'nuptk' => '603',
            'nama' => 'Wali B',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelasA = Kelas::create(['nama' => 'A', 'guru_id' => $waliA->id, 'tahun_ajaran_id' => $ta->id]);
        $kelasB = Kelas::create(['nama' => 'B', 'guru_id' => $waliB->id, 'tahun_ajaran_id' => $ta->id]);

        Pengajaran::create(['guru_id' => $teacherA->id, 'mapel_id' => $mapel1->id, 'kelas' => $kelasA->nama]);
        Pengajaran::create(['guru_id' => $teacherB->id, 'mapel_id' => $mapel2->id, 'kelas' => $kelasA->nama]);
        Pengajaran::create(['guru_id' => $teacherA->id, 'mapel_id' => $mapel1->id, 'kelas' => $kelasB->nama]);
        Pengajaran::create(['guru_id' => $teacherB->id, 'mapel_id' => $mapel2->id, 'kelas' => $kelasB->nama]);

        $response = $this->actingAs($admin)->post('/jadwal/generate');
        $response->assertRedirect('/jadwal');
        $response->assertSessionMissing('error');

        $this->assertEquals(16, Jadwal::count());

        foreach ([$kelasA, $kelasB] as $kelas) {
            foreach ([$mapel1, $mapel2] as $mapel) {
                $this->assertEquals(4, Jadwal::where('kelas_id', $kelas->id)->where('mapel_id', $mapel->id)->count());
            }
        }
    }
  
    public function test_generate_schedule_skips_classes_without_pengajaran(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $teacher = Guru::create([
            'nuptk' => '700',
            'nama' => 'Guru C',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1987-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPS']);

        $waliA = Guru::create([
            'nuptk' => '701',
            'nama' => 'Wali C',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1991-01-01',
        ]);
        $waliB = Guru::create([
            'nuptk' => '702',
            'nama' => 'Wali D',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1991-01-01',
        ]);
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelasA = Kelas::create(['nama' => 'C', 'guru_id' => $waliA->id, 'tahun_ajaran_id' => $ta->id]);
        $kelasB = Kelas::create(['nama' => 'D', 'guru_id' => $waliB->id, 'tahun_ajaran_id' => $ta->id]);

        Pengajaran::create(['guru_id' => $teacher->id, 'mapel_id' => $mapel->id, 'kelas' => $kelasA->nama]);

        $this->actingAs($admin)->post('/jadwal/generate')->assertRedirect('/jadwal');

        $this->assertEquals(4, Jadwal::where('kelas_id', $kelasA->id)->count());
        $this->assertEquals(0, Jadwal::where('kelas_id', $kelasB->id)->count());
    }

    public function test_generate_schedule_creates_pengajaran_when_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $teacher = Guru::create([
            'nuptk' => '800',
            'nama' => 'Guru E',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1988-01-01',
        ]);

        $mapel = MataPelajaran::create(['nama' => 'Sejarah']);

        $wali = Guru::create([
            'nuptk' => '801',
            'nama' => 'Wali E',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1992-01-01',
        ]);

        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        $kelas = Kelas::create(['nama' => 'E', 'guru_id' => $wali->id, 'tahun_ajaran_id' => $ta->id]);

        $this->actingAs($admin)->post('/jadwal/generate')->assertRedirect('/jadwal');

        $this->assertEquals(1, Pengajaran::count());
        $this->assertEquals(4, Jadwal::count());

        $pengajaran = Pengajaran::first();
        $this->assertEquals($mapel->id, $pengajaran->mapel_id);
        $this->assertEquals($kelas->nama, $pengajaran->kelas);
        $this->assertTrue(Guru::pluck('id')->contains($pengajaran->guru_id));
    }
}
