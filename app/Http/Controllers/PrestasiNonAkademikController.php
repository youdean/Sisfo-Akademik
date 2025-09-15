<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrestasiNonAkademik;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class PrestasiNonAkademikController extends Controller
{
    public function create()
    {
        $siswa = Siswa::all();
        $tahunAjaran = TahunAjaran::all();
        return view('prestasi_non_akademik.create', compact('siswa', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_prestasi' => 'required|string',
            'tingkat' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        PrestasiNonAkademik::create($request->all());
        return redirect()->back()->with('success', 'Data prestasi non akademik berhasil disimpan');
    }
}
