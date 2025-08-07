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

    private function hasConflict(array $entries, array $slot): bool
    {
        foreach ($entries as $entry) {
            if ($entry[0] < $slot[1] && $entry[1] > $slot[0]) {
                return true;
            }
        }
        return false;
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
        $slotPairs = [];
        for ($i = 0; $i < count($slots) - 1; $i++) {
            $slotPairs[] = [$slots[$i], $slots[$i + 1]];
        }
        $errors = [];
        $requiredSlots = 4;

        if (Pengajaran::count() === 0) {
            $guruIds = Guru::pluck('id')->values();
            $mapelList = MataPelajaran::all();
            if ($guruIds->isEmpty() || $mapelList->isEmpty()) {
                return redirect()->route('jadwal.index')->with('error', 'Tidak ada data pengajaran');
            }
            foreach (Kelas::all() as $kelasAuto) {
                $index = 0;
                foreach ($mapelList as $mapelAuto) {
                    $guruId = $guruIds[$index % $guruIds->count()];
                    Pengajaran::create([
                        'guru_id' => $guruId,
                        'mapel_id' => $mapelAuto->id,
                        'kelas' => $kelasAuto->nama,
                    ]);
                    $index++;
                }
            }
        }

        $teacherSchedules = [];
        $classSchedules = [];
        Jadwal::query()->delete();

        foreach (Kelas::all() as $kelas) {
            $pengajarans = Pengajaran::where('kelas', $kelas->nama)->get();
            if ($pengajarans->isEmpty()) {
                continue;
            }

            $classSchedules[$kelas->id] = array_fill_keys($days, []);
            $globalDayUsage = array_fill_keys($days, 0);

            foreach ($pengajarans as $pengajaran) {
                $teacherSchedules[$pengajaran->guru_id] = $teacherSchedules[$pengajaran->guru_id] ?? array_fill_keys($days, []);
                $success = false;

                for ($attempt = 0; $attempt < 20 && !$success; $attempt++) {
                    $created = [];
                    $dayCounts = array_fill_keys($days, 0);
                    $dayOrder = $days;
                    usort($dayOrder, function ($a, $b) use ($globalDayUsage) {
                        $cmp = $globalDayUsage[$a] <=> $globalDayUsage[$b];
                        return $cmp === 0 ? (random_int(0, 1) ? 1 : -1) : $cmp;
                    });

                    foreach ($dayOrder as $day) {
                        $pairOrder = $slotPairs;
                        shuffle($pairOrder);
                        foreach ($pairOrder as $pair) {
                            if (count($created) >= $requiredSlots) {
                                break 2;
                            }

                            if ($dayCounts[$day] >= 2) {
                                continue;
                            }

                            [$slot1, $slot2] = $pair;

                            if ($this->hasConflict($teacherSchedules[$pengajaran->guru_id][$day], $slot1) ||
                                $this->hasConflict($teacherSchedules[$pengajaran->guru_id][$day], $slot2)) {
                                continue;
                            }

                            if ($this->hasConflict($classSchedules[$kelas->id][$day], $slot1) ||
                                $this->hasConflict($classSchedules[$kelas->id][$day], $slot2)) {
                                continue;
                            }

                            $created[] = [
                                'kelas_id' => $kelas->id,
                                'mapel_id' => $pengajaran->mapel_id,
                                'guru_id' => $pengajaran->guru_id,
                                'hari' => $day,
                                'jam_mulai' => $slot1[0],
                                'jam_selesai' => $slot1[1],
                            ];
                            $created[] = [
                                'kelas_id' => $kelas->id,
                                'mapel_id' => $pengajaran->mapel_id,
                                'guru_id' => $pengajaran->guru_id,
                                'hari' => $day,
                                'jam_mulai' => $slot2[0],
                                'jam_selesai' => $slot2[1],
                            ];
                            $dayCounts[$day] += 2;
                        }
                    }

                    if (count($created) >= $requiredSlots) {
                        foreach ($created as $data) {
                            Jadwal::create($data);
                            $this->syncPengajaran($data);
                            $teacherSchedules[$data['guru_id']][$data['hari']][] = [$data['jam_mulai'], $data['jam_selesai']];
                            $classSchedules[$data['kelas_id']][$data['hari']][] = [$data['jam_mulai'], $data['jam_selesai']];
                        }
                        foreach ($dayCounts as $d => $count) {
                            $globalDayUsage[$d] += $count;
                        }
                        $success = true;
                    }
                }

                if (!$success) {
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
