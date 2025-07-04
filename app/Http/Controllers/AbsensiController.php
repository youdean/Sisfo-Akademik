<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\Siswa;

class AbsensiController extends Controller
{

public function index()
{
    $absensi = Absensi::with('siswa')->get();
    return view('absensi.index', compact('absensi'));
}

public function create()
{
    $siswa = Siswa::all();
    return view('absensi.create', compact('siswa'));
}

public function store(Request $request)
{
    Absensi::create($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]));

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan');
}

public function edit(Absensi $absensi)
{
    $siswa = Siswa::all();
    return view('absensi.edit', compact('absensi', 'siswa'));
}

public function update(Request $request, Absensi $absensi)
{
    $absensi->update($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]));

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate');
}

public function destroy(Absensi $absensi)
{
    $absensi->delete();
    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
}
}
