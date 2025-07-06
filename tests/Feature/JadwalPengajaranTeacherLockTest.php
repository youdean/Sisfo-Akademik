<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Pengajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPengajaranTeacherLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_assign_new_teacher_if_subject_already_has_teacher_in_class(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guruA = Guru::create([
            'nip' => '5001',
            'nama' => 'Guru A',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $guruB = Guru::create([
            'nip' => '5002',
            'nama' => 'Guru B',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Bahasa Indonesia']);
        $kelas = Kelas::create(['nama' => '10A']);

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guruA->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $response = $this->actingAs($user)
            ->from('/jadwal/create')
            ->post('/jadwal', [
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guruB->id,
                'hari' => 'Selasa',
                'jam_mulai' => '09:00',
                'jam_selesai' => '10:00',
            ]);

        $response->assertRedirect('/jadwal/create');
        $response->assertSessionHas('error');
        $this->assertEquals(1, Jadwal::count());
        $this->assertDatabaseHas('pengajaran', [
            'guru_id' => $guruA->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);
    }
}
