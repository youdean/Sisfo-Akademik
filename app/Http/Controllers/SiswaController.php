<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index()
{
    $siswa = Siswa::all();
    return view('siswa.index', compact('siswa'));
}

public function create()
{
    return view('siswa.create');
}

public function store(Request $request)
{
    Siswa::create($request->validate([
        'nama' => 'required',
        'kelas' => 'required',
        'tanggal_lahir' => 'required|date'
    ]));

    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
}

public function edit(Siswa $siswa)
{
    return view('siswa.edit', compact('siswa'));
}

public function update(Request $request, Siswa $siswa)
{
    $siswa->update($request->validate([
        'nama' => 'required',
        'kelas' => 'required',
        'tanggal_lahir' => 'required|date'
    ]));

    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate');
}

public function destroy(Siswa $siswa)
{
    $siswa->delete();
    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
}

public function export()
{
    return Excel::download(new SiswaExport, 'data-siswa.xlsx');
}
}
