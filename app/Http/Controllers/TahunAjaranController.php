<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahun_ajaran = TahunAjaran::paginate(10)->withQueryString();
        return view('tahun_ajaran.index', compact('tahun_ajaran'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        TahunAjaran::create($request->validate([
            'nama' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]));

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        return view('tahun_ajaran.edit', compact('tahun_ajaran'));
    }

    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $tahun_ajaran->update($request->validate([
            'nama' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]));

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diupdate');
    }

    public function destroy(TahunAjaran $tahun_ajaran)
    {
        $tahun_ajaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
