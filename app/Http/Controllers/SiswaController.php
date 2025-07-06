<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $siswa = $query->paginate(10)->withQueryString();

        return view('siswa.index', compact('siswa', 'search'));
    }

public function create()
{
    $kelas = Kelas::all();
    return view('siswa.create', compact('kelas'));
}

public function store(Request $request)
{
    Siswa::create($request->validate([
        'nama' => 'required',
        'nisn' => 'required|unique:siswa,nisn',
        'kelas' => 'required',
        'tempat_lahir' => 'required',
        'jenis_kelamin' => 'required',
        'tanggal_lahir' => 'required|date'
    ]));

    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
}

public function edit(Siswa $siswa)
{
    $kelas = Kelas::all();
    return view('siswa.edit', compact('siswa', 'kelas'));
}

public function update(Request $request, Siswa $siswa)
{
    $siswa->update($request->validate([
        'nama' => 'required',
        'nisn' => 'required|unique:siswa,nisn,' . $siswa->id,
        'kelas' => 'required',
        'tempat_lahir' => 'required',
        'jenis_kelamin' => 'required',
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
