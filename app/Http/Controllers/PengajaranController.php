<?php

namespace App\Http\Controllers;

use App\Models\Pengajaran;
use Illuminate\Http\Request;

class PengajaranController extends Controller
{
    public function index()
    {
        return Pengajaran::with(['guru', 'mapel'])->get();
    }

    public function store(Request $request)
    {
        return Pengajaran::create($request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas' => 'required|string'
        ]));
    }

    public function destroy(Pengajaran $pengajaran)
    {
        $pengajaran->delete();
        return response()->noContent();
    }
}
