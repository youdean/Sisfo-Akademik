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
                    ->orWhere('nuptk', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $guru = $query->paginate(10)->withQueryString();

        return view('guru.index', compact('guru', 'search'));
    }

public function create()
{
    return view('guru.create');
}

public function store(Request $request)
{
    Guru::create($request->validate([
        'nuptk' => 'required|unique:guru',
        'nama' => 'required',
        'jabatan' => 'nullable',
        'email' => 'nullable|email|unique:guru',
        'tempat_lahir' => 'required',
        'jenis_kelamin' => 'required',
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
        'nuptk' => 'required|unique:guru,nuptk,' . $guru->id,
        'nama' => 'required',
        'jabatan' => 'nullable',
        'email' => 'nullable|email|unique:guru,email,' . $guru->id,
        'tempat_lahir' => 'required',
        'jenis_kelamin' => 'required',
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
