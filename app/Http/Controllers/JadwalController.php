<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with(['kelas', 'mapel', 'guru'])->get()->groupBy('hari');
        return view('jadwal.index', compact('jadwal'));
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
            'jam_selesai' => 'required',
        ]);
        Jadwal::create($data);

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
            'jam_selesai' => 'required',
        ]);
        $jadwal->update($data);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diupdate');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
