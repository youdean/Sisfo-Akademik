<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('waliKelas')->paginate(10)->withQueryString();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = Guru::all();
        return view('kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        Kelas::create($request->validate([
            'nama' => 'required',
            'guru_id' => 'required|exists:guru,id|unique:kelas,guru_id'
        ]));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kela)
    {
        $guru = Guru::all();
        return view('kelas.edit', ['kela' => $kela, 'guru' => $guru]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $kela->update($request->validate([
            'nama' => 'required',
            'guru_id' => 'required|exists:guru,id|unique:kelas,guru_id,' . $kela->id
        ]));

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}
