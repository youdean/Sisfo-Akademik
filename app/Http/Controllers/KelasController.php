<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])->paginate(10)->withQueryString();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = Guru::all();
        $tahun_ajaran = TahunAjaran::all();
        return view('kelas.create', compact('guru', 'tahun_ajaran'));
    }

    public function store(Request $request)
    {
        Kelas::create($request->validate([
            'nama' => 'required',
            'guru_id' => 'required|exists:guru,id|unique:kelas,guru_id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id'
        ]));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kela)
    {
        $guru = Guru::all();
        $tahun_ajaran = TahunAjaran::all();
        return view('kelas.edit', ['kela' => $kela, 'guru' => $guru, 'tahun_ajaran' => $tahun_ajaran]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $kela->update($request->validate([
            'nama' => 'required',
            'guru_id' => 'required|exists:guru,id|unique:kelas,guru_id,' . $kela->id,
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id'
        ]));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}
