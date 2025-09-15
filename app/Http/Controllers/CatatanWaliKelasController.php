<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatatanWaliKelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class CatatanWaliKelasController extends Controller
{
    public function create()
    {
        $siswa = Siswa::all();
        $tahunAjaran = TahunAjaran::all();
        return view('catatan_wali_kelas.create', compact('siswa', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'catatan' => 'nullable|string',
        ]);
        CatatanWaliKelas::create($request->all());
        return redirect()->back()->with('success', 'Catatan wali kelas berhasil disimpan');
    }
}
