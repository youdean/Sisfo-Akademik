<?php

namespace App\Http\Controllers;

use App\Models\Pengajaran;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class PengajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajaran::with(['guru', 'mapel']);

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kelas', 'like', "%{$search}%")
                    ->orWhereHas('guru', function ($g) use ($search) {
                        $g->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mapel', function ($m) use ($search) {
                        $m->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $pengajaran = $query->get();

        return view('pengajaran.index', compact('pengajaran', 'search'));
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
