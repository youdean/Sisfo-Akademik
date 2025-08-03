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
    }

    public function test_non_admin_cannot_access_generate_schedule_endpoint(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $this->actingAs($user)->post('/jadwal/generate')->assertStatus(403);
    }
}
