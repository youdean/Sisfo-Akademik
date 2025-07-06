<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Pengajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NilaiController extends Controller
{
    private function kelasGuru(): array
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if (!$guru) {
            return [];
        }
        return Pengajaran::where('guru_id', $guru->id)->pluck('kelas')->toArray();
    }
    public function index(Request $request)
    {
        $query = Nilai::with(['siswa', 'mapel'])->whereHas('siswa', function ($q) {
            $q->whereIn('kelas', $this->kelasGuru());
        });

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nilai', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($s) use ($search) {
                        $s->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mapel', function ($m) use ($search) {
                        $m->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $nilai = $query->paginate(10)->withQueryString();

        return view('nilai.index', compact('nilai', 'search'));
    }

public function create()
{
    $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
    $mapel = MataPelajaran::all();
    return view('nilai.create', compact('siswa', 'mapel'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
        'mapel_id' => 'required|exists:mata_pelajaran,id',
        'nilai' => 'required|integer|min:0|max:100',
        'semester' => 'required|integer|in:1,2'
    ]);
    Nilai::create($data);

    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil ditambahkan');
}

public function edit(Nilai $nilai)
{
    if (!in_array($nilai->siswa->kelas, $this->kelasGuru())) {
        abort(403);
    }
    $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
    $mapel = MataPelajaran::all();
    return view('nilai.edit', compact('nilai', 'siswa', 'mapel'));
}

public function update(Request $request, Nilai $nilai)
{
    if (!in_array($nilai->siswa->kelas, $this->kelasGuru())) {
        abort(403);
    }
    $data = $request->validate([
        'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
        'mapel_id' => 'required|exists:mata_pelajaran,id',
        'nilai' => 'required|integer|min:0|max:100',
        'semester' => 'required|integer|in:1,2'
    ]);
    $nilai->update($data);

    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil diupdate');
}

public function destroy(Nilai $nilai)
{
    if (!in_array($nilai->siswa->kelas, $this->kelasGuru())) {
        abort(403);
    }
    $nilai->delete();
    return redirect()->route('nilai.index')->with('success', 'Nilai berhasil dihapus');
}
}
