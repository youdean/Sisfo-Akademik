<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\MataPelajaran;

class NilaiController extends Controller
{
    public function index()
{
    $nilai = Nilai::with(['siswa', 'mapel'])->get();
    return view('nilai.index', compact('nilai'));
}

public function create()
{
    $siswa = Siswa::all();
    $mapel = MataPelajaran::all();
    return view('nilai.create', compact('siswa', 'mapel'));
}

public function store(Request $request)
{
    Nilai::create($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'mapel_id' => 'required|exists:mata_pelajaran,id',
        'nilai' => 'required|integer|min:0|max:100'
    ]));

    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan');
}

public function edit(Nilai $nilai)
{
    $siswa = Siswa::all();
    $mapel = MataPelajaran::all();
    return view('nilai.edit', compact('nilai', 'siswa', 'mapel'));
}

public function update(Request $request, Nilai $nilai)
{
    $nilai->update($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'mapel_id' => 'required|exists:mata_pelajaran,id',
        'nilai' => 'required|integer|min:0|max:100'
    ]));

    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diupdate');
}

public function destroy(Nilai $nilai)
{
    $nilai->delete();
    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus');
}
}
