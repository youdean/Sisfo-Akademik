<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Pengajaran;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    private function syncPengajaran(array $data): void
    {
        $kelasNama = Kelas::find($data['kelas_id'])->nama ?? null;
        if (!$kelasNama) {
            return;
        }
        Pengajaran::updateOrCreate(
            [
                'mapel_id' => $data['mapel_id'],
                'kelas' => $kelasNama,
            ],
            [
                'guru_id' => $data['guru_id'],
            ]
        );
    }
    public function index(Request $request)
    {
        $kelasId = $request->query('kelas');
        $kelasList = Kelas::pluck('nama', 'id');

        $query = Jadwal::with(['kelas', 'mapel', 'guru']);
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $jadwal = $query->orderBy('jam_mulai')->get()->groupBy('hari');

        return view('jadwal.index', [
            'jadwal' => $jadwal,
            'kelasList' => $kelasList,
            'selectedKelas' => $kelasId,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = Guru::all();
        return view('jadwal.create', compact('kelas', 'mapel', 'guru'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'guru_id'  => 'required|exists:guru,id',
            'hari'     => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);
        $exists = Jadwal::where('guru_id', $data['guru_id'])
            ->where('hari', $data['hari'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Guru sudah dijadwalkan pada jam tersebut');
        }

        $kelasNama = Kelas::find($data['kelas_id'])->nama ?? null;
        $pengajaranExists = Pengajaran::where('mapel_id', $data['mapel_id'])
            ->where('kelas', $kelasNama)
            ->where('guru_id', '!=', $data['guru_id'])
            ->exists();

        if ($pengajaranExists) {
            return back()->withInput()->with('error', 'Kelas sudah memiliki guru untuk mata pelajaran tersebut');
        }
        Jadwal::create($data);
        $this->syncPengajaran($data);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(Jadwal $jadwal)
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = Guru::all();
        return view('jadwal.edit', compact('jadwal', 'kelas', 'mapel', 'guru'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $data = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'guru_id'  => 'required|exists:guru,id',
            'hari'     => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);
        $exists = Jadwal::where('guru_id', $data['guru_id'])
            ->where('hari', $data['hari'])
            ->where('id', '!=', $jadwal->id)
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Guru sudah dijadwalkan pada jam tersebut');
        }

        $kelasNama = Kelas::find($data['kelas_id'])->nama ?? null;
        $pengajaranExists = Pengajaran::where('mapel_id', $data['mapel_id'])
            ->where('kelas', $kelasNama)
            ->where('guru_id', '!=', $data['guru_id'])
            ->exists();

        if ($pengajaranExists) {
            return back()->withInput()->with('error', 'Kelas sudah memiliki guru untuk mata pelajaran tersebut');
        }
        $jadwal->update($data);
        $this->syncPengajaran($data);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function generate(Request $request)
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $slots = [
            ['07:00', '08:00'],
            ['08:00', '09:00'],
            ['09:00', '10:00'],
            ['10:00', '11:00'],
            ['11:00', '12:00'],
            ['12:00', '13:00'],
            ['13:00', '14:00'],
            ['14:00', '15:00'],
        ];
        $errors = [];

        foreach (Kelas::all() as $kelas) {
            Jadwal::where('kelas_id', $kelas->id)->delete();
            $pengajarans = Pengajaran::where('kelas', $kelas->nama)->get();
            foreach ($pengajarans as $pengajaran) {
                $created = 0;
                $dayCounts = array_fill_keys($days, 0);
                $daySlots = array_fill_keys($days, []);
                $attempts = 0;
                while ($created < 4 && $attempts < 10) {
                    $attempts++;
                    $shuffledDays = $days;
                    shuffle($shuffledDays);
                    foreach ($shuffledDays as $day) {
                        if ($dayCounts[$day] >= 2) {
                            continue;
                        }
                        $shuffledSlots = $slots;
                        shuffle($shuffledSlots);
                        foreach ($shuffledSlots as $slot) {
                            if ($dayCounts[$day] >= 2 || $created >= 4) {
                                break;
                            }
                            $teacherConflict = Jadwal::where('guru_id', $pengajaran->guru_id)
                                ->where('hari', $day)
                                ->where('jam_mulai', $slot[0])
                                ->exists();
                            $classConflict = Jadwal::where('kelas_id', $kelas->id)
                                ->where('hari', $day)
                                ->where('jam_mulai', $slot[0])
                                ->exists();
                            if ($teacherConflict || $classConflict) {
                                continue;
                            }
                            $prevSlot = date('H:i', strtotime($slot[0] . ' -1 hour'));
                            $nextSlot = date('H:i', strtotime($slot[0] . ' +1 hour'));
                            if ($dayCounts[$day] == 1 && !in_array($prevSlot, $daySlots[$day]) && !in_array($nextSlot, $daySlots[$day])) {
                                continue;
                            }
                            $data = [
                                'kelas_id' => $kelas->id,
                                'mapel_id' => $pengajaran->mapel_id,
                                'guru_id' => $pengajaran->guru_id,
                                'hari' => $day,
                                'jam_mulai' => $slot[0],
                                'jam_selesai' => $slot[1],
                            ];
                            Jadwal::create($data);
                            $this->syncPengajaran($data);
                            $created++;
                            $dayCounts[$day]++;
                            $daySlots[$day][] = $slot[0];
                        }
                        if ($created >= 4) {
                            break;
                        }
                    }
                }
                if ($created < 4) {
                    $mapelName = MataPelajaran::find($pengajaran->mapel_id)->nama ?? 'Mapel';
                    $errors[] = "Slot tidak cukup untuk {$kelas->nama} - {$mapelName}";
                }
            }
        }

        if ($errors) {
            return redirect()->route('jadwal.index')->with('error', implode(', ', $errors));
        }

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil digenerate');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
