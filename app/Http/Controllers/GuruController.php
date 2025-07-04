<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
{
    $guru = Guru::all();
    return view('guru.index', compact('guru'));
}

public function create()
{
    return view('guru.create');
}

public function store(Request $request)
{
    Guru::create($request->validate([
        'nip' => 'required|unique:guru',
        'nama' => 'required'
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
        'nama' => 'required'
    ]));

    return redirect()->route('guru.index')->with('success', 'Guru berhasil diupdate');
}

public function destroy(Guru $guru)
{
    $guru->delete();
    return redirect()->route('guru.index')->with('success', 'Guru berhasil dihapus');
}

}
