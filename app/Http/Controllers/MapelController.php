<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = MataPelajaran::paginate(10)->withQueryString();
        return view('mapel.index', compact('mapel')); 
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        MataPelajaran::create($request->validate([
            'nama' => 'required'
        ]));

        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil ditambahkan');
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $mapel->update($request->validate([
            'nama' => 'required'
        ]));

        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil diupdate');
    }

    public function destroy(MataPelajaran $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil dihapus');
    }
}
