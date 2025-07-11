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

        $jadwal = $query->get()->groupBy('hari');

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

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
