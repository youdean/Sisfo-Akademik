<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahunAjaran = TahunAjaran::all();
        return view('tahunajaran.index', compact('tahunAjaran'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tahunajaran.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        TahunAjaran::create($request->validate([
            'tahun' => 'required|unique:tahun_ajarans,tahun'
        ]));

        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        return view('tahunajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->update($request->validate([
            'tahun' => 'required|unique:tahun_ajarans,tahun,' . $tahunAjaran->id
        ]));

        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->delete();
        return redirect()->route('tahunajaran.index')->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
