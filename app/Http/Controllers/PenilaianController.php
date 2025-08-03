<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Pengajaran;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PenilaianController extends Controller
{
    private function kelasGuru(): array
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return Kelas::pluck('nama')->toArray();
        }

        $guru = Guru::where('user_id', $user?->id)->first();
        if (!$guru) {
            return [];
        }
        $pengajaranKelas = Pengajaran::where('guru_id', $guru->id)->pluck('kelas');
        $waliKelas = Kelas::where('guru_id', $guru->id)->pluck('nama');

        return $pengajaranKelas->merge($waliKelas)->unique()->toArray();
    }

    public function index(Request $request)
    {
        $query = Penilaian::with('siswa')->whereHas('siswa', function ($q) {
            $q->whereIn('kelas', $this->kelasGuru());
        });

        $search = $request->input('search');
        if ($search) {
            $query->whereHas('siswa', function ($s) use ($search) {
                $s->where('nama', 'like', "%{$search}%");
            });
        }

        $penilaian = $query->paginate(10)->withQueryString();
        return view('penilaian.index', compact('penilaian', 'search'));
    }

    public function create()
    {
        $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
        $mapel = MataPelajaran::all();
        return view('penilaian.create', compact('siswa', 'mapel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'semester' => 'required|integer|in:1,2',
            'hadir' => 'required|integer|min:0',
            'sakit' => 'required|integer|min:0',
            'izin' => 'required|integer|min:0',
            'alpha' => 'required|integer|min:0',
            'pts' => 'nullable|integer|min:0|max:100',
            'pat' => 'nullable|integer|min:0|max:100',
        ]);

        Penilaian::create($data);

        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil ditambahkan');
    }

    public function destroy(Penilaian $penilaian)
    {
        if (!in_array($penilaian->siswa->kelas, $this->kelasGuru())) {
            abort(403);
        }
        $penilaian->delete();
        return redirect()->route('penilaian.index')->with('success', 'Data penilaian berhasil dihapus');
    }
}
