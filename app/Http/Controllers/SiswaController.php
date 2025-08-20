<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('tahunAjaran');

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%");
            });
        }

        $tahunAjaranId = $request->input('tahun_ajaran_id');
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        $siswa = $query->paginate(10)->withQueryString();
        $tahun_ajaran = TahunAjaran::all();

        return view('siswa.index', compact('siswa', 'search', 'tahun_ajaran', 'tahunAjaranId'));
    }

public function create()
{
    $kelas = Kelas::all();
    $tahun_ajaran = TahunAjaran::all();
    return view('siswa.create', compact('kelas', 'tahun_ajaran'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'nama' => 'required',
        'nisn' => 'required|unique:siswa,nisn',
        'nama_ortu' => 'required',
        'kelas' => 'required',
        'tempat_lahir' => 'required',
        'jenis_kelamin' => 'required',
        'tanggal_lahir' => 'required|date',
        'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id'
    ]);
    $siswa = Siswa::create($data);

    // Otomatis buat user jika siswa aktif dan belum punya user
    if (!isset($siswa->user_id) || !$siswa->user_id) {
        $user = \App\Models\User::create([
            'name' => $siswa->nisn . ' - ' . $siswa->nama,
            'email' => $siswa->nisn . '@muhammadiyah.ac.id',
            'password' => bcrypt(date('ymd', strtotime($siswa->tanggal_lahir))),
            'role' => 'siswa',
            'status' => 1,
        ]);
        $siswa->user_id = $user->id;
        $siswa->save();
    }

    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
}

public function edit(Siswa $siswa)
{
    $kelas = Kelas::all();
    $tahun_ajaran = TahunAjaran::all();
    return view('siswa.edit', compact('siswa', 'kelas', 'tahun_ajaran'));
}

public function update(Request $request, Siswa $siswa)
{
        $siswa->update($request->validate([
            'nama' => 'required',
            'nisn' => 'required|unique:siswa,nisn,' . $siswa->id,
            'nama_ortu' => 'required',
            'kelas' => 'required',
            'tempat_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id'
        ]));

    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diupdate');
}

public function destroy(Siswa $siswa)
{
    $siswa->delete();
    return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
}

public function export()
{
    return Excel::download(new SiswaExport, 'data-siswa.xlsx');
}
}
