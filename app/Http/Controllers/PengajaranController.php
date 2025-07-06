<?php

namespace App\Http\Controllers;

use App\Models\Pengajaran;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

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
        $kelas = Kelas::all();
        return view('pengajaran.create', compact('guru', 'mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_nama' => ['required', 'exists:guru,nama'],
            'mapel_id' => ['required', 'exists:mata_pelajaran,id'],
            'kelas' => ['required', 'exists:kelas,nama'],
        ]);

        $guru = Guru::where('nama', $validated['guru_nama'])->first();
        $exists = Pengajaran::where('mapel_id', $validated['mapel_id'])
            ->where('kelas', $validated['kelas'])
            ->exists();
        if ($exists) {
            return back()->withInput()->with('error', 'Data pengajaran sudah ada');
        }

        try {
            Pengajaran::create([
                'guru_id' => $guru->id,
                'mapel_id' => (int) $validated['mapel_id'],
                'kelas' => $validated['kelas'],
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withInput()->with('error', 'Data pengajaran sudah ada');
            }
            throw $e;
        }

        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil ditambahkan');
    }

    public function edit(Pengajaran $pengajaran)
    {
        $guru = Guru::all();
        $mapel = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('pengajaran.edit', compact('pengajaran', 'guru', 'mapel', 'kelas'));
    }

    public function update(Request $request, Pengajaran $pengajaran)
    {
        $validated = $request->validate([
            'guru_nama' => ['required', 'exists:guru,nama'],
            'mapel_id' => ['required', 'exists:mata_pelajaran,id'],
            'kelas' => ['required', 'exists:kelas,nama'],
        ]);

        $guru = Guru::where('nama', $validated['guru_nama'])->first();
        $exists = Pengajaran::where('mapel_id', $validated['mapel_id'])
            ->where('kelas', $validated['kelas'])
            ->where('id', '!=', $pengajaran->id)
            ->exists();
        if ($exists) {
            return back()->withInput()->with('error', 'Data pengajaran sudah ada');
        }

        try {
            $pengajaran->update([
                'guru_id' => $guru->id,
                'mapel_id' => (int) $validated['mapel_id'],
                'kelas' => $validated['kelas'],
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return back()->withInput()->with('error', 'Data pengajaran sudah ada');
            }
            throw $e;
        }

        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil diupdate');
    }

    public function destroy(Pengajaran $pengajaran)
    {
        $pengajaran->delete();
        return redirect()->route('pengajaran.index')->with('success', 'Data pengajaran berhasil dihapus');
    }
}
