<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $guru = $query->get();

        return view('guru.index', compact('guru', 'search'));
    }

public function create()
{
    return view('guru.create');
}

public function store(Request $request)
{
    Guru::create($request->validate([
        'nip' => 'required|unique:guru',
        'nama' => 'required',
        'tanggal_lahir' => 'required|date'
    ]));

    return redirect()->route('guru.index')->with('success', 'Guru berhasil ditambahkan');
}

public function edit(Guru $guru)
{
    return view('guru.edit', compact('guru'));
}

public function update(Request $request, Guru $guru)
{
    $guru->update($request->validate([
        'nip' => 'required|unique:guru,nip,' . $guru->id,
        'nama' => 'required',
        'tanggal_lahir' => 'required|date'
    ]));

    return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate');
}

public function destroy(Guru $guru)
{
    $guru->delete();
    return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus');
}

}
