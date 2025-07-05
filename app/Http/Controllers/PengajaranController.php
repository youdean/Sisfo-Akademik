<?php

namespace App\Http\Controllers;

use App\Models\Pengajaran;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class PengajaranController extends Controller
{
    public function index()
    {
        $pengajaran = Pengajaran::with(['guru', 'mapel'])->get();
        return view('pengajaran.index', compact('pengajaran'));
    }

    public function create()
    {
        $guru = Guru::all();
        $mapel = MataPelajaran::all();
        return view('pengajaran.create', compact('guru', 'mapel'));
    }

    public function store(Request $request)
    {
        Pengajaran::create($request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas' => 'required|string'
        ]));

        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil ditambahkan');
    }

    public function edit(Pengajaran $pengajaran)
    {
        $guru = Guru::all();
        $mapel = MataPelajaran::all();
        return view('pengajaran.edit', compact('pengajaran', 'guru', 'mapel'));
    }

    public function update(Request $request, Pengajaran $pengajaran)
    {
        $pengajaran->update($request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas' => 'required|string'
        ]));

        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil diupdate');
    }

    public function destroy(Pengajaran $pengajaran)
    {
        $pengajaran->delete();
        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil dihapus');
    }
}
