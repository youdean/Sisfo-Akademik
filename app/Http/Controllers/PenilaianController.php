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
use Illuminate\Support\Facades\DB;
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
        $query = Penilaian::with(['siswa', 'mapel'])->whereHas('siswa', function ($q) {
            $q->whereIn('kelas', $this->kelasGuru());
        });

        $user = Auth::user();
        if ($user && $user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->whereExists(function ($sub) use ($guru) {
                    $sub->select(DB::raw(1))
                        ->from('pengajaran')
                        ->join('siswa', 'pengajaran.kelas', '=', 'siswa.kelas')
                        ->whereColumn('siswa.id', 'penilaian.siswa_id')
                        ->whereColumn('pengajaran.mapel_id', 'penilaian.mapel_id')
                        ->where('pengajaran.guru_id', $guru->id);
                });
            }
        }

        $nama = $request->input('nama');
        if ($nama) {
            $query->whereHas('siswa', function ($s) use ($nama) {
                $s->where('nama', 'like', "%{$nama}%");
            });
        }

        $kelas = $request->input('kelas');
        if ($kelas) {
            $query->whereHas('siswa', function ($s) use ($kelas) {
                $s->where('kelas', 'like', "%{$kelas}%");
            });
        }

        $mapel = $request->input('mapel');
        if ($mapel) {
            $query->whereHas('mapel', function ($m) use ($mapel) {
                $m->where('nama', 'like', "%{$mapel}%");
            });
        }

        $penilaian = $query->paginate(10)->withQueryString();
        return view('penilaian.index', compact('penilaian', 'nama', 'kelas', 'mapel'));
    }

    public function create()
    {
        $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();

        $user = Auth::user();
        if ($user && $user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            $mapelIds = Pengajaran::where('guru_id', $guru?->id)->pluck('mapel_id')->unique();
            $mapel = MataPelajaran::whereIn('id', $mapelIds)->get();
        } else {
            $mapel = MataPelajaran::all();
        }

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

        $user = Auth::user();
        if ($user && $user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            $kelas = Siswa::where('id', $data['siswa_id'])->value('kelas');
            $allowed = Pengajaran::where('guru_id', $guru?->id)
                ->where('mapel_id', $data['mapel_id'])
                ->where('kelas', $kelas)
                ->exists();
            if (!$allowed) {
                abort(403);
            }
        }

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
