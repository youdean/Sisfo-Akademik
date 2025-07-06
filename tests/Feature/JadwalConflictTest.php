<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalConflictTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_cannot_have_overlapping_schedule(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '111',
            'nama' => 'Guru A',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $kelasA = Kelas::create(['nama' => 'A']);
        $kelasB = Kelas::create(['nama' => 'B']);

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelasA->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $response = $this->actingAs($user)
            ->from('/jadwal/create')
            ->post('/jadwal', [
                'kelas_id' => $kelasB->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guru->id,
                'hari' => 'Senin',
                'jam_mulai' => '07:30',
                'jam_selesai' => '08:30',
            ]);

        $response->assertRedirect('/jadwal/create');
        $response->assertSessionHas('error', 'Guru sudah dijadwalkan pada jam tersebut');

        $this->assertEquals(1, Jadwal::count());
    }

    public function test_teacher_can_have_consecutive_schedule(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '222',
            'nama' => 'Guru B',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        $kelasA = Kelas::create(['nama' => 'A']);
        $kelasB = Kelas::create(['nama' => 'B']);

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelasA->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $response = $this->actingAs($user)
            ->post('/jadwal', [
                'kelas_id' => $kelasB->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guru->id,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:00',
            ]);

        $response->assertRedirect('/jadwal');
        $response->assertSessionMissing('error');

        $this->assertEquals(2, Jadwal::count());
    }
}

