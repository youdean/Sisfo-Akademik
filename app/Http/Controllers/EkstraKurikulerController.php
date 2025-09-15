<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EkstraKurikuler;
use App\Models\Siswa;
use App\Models\TahunAjaran;

class EkstraKurikulerController extends Controller
{
    public function create()
    {
        $siswa = Siswa::all();
        $tahunAjaran = TahunAjaran::all();
        return view('ekstra_kurikuler.create', compact('siswa', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_ekstra' => 'required|string',
            'nilai' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);
        EkstraKurikuler::create($request->all());
        return redirect()->back()->with('success', 'Data ekstra kurikuler berhasil disimpan');
    }
}
