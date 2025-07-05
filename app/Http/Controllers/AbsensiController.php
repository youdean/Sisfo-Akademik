<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Pengajaran;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AbsensiController extends Controller
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
        $query = Absensi::with('siswa')->whereHas('siswa', function ($q) {
            $q->whereIn('kelas', $this->kelasGuru());
        });

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhere('tanggal', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($s) use ($search) {
                        $s->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        $absensi = $query->get();

        return view('absensi.index', compact('absensi', 'search'));
    }

public function create()
{
    $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
    return view('absensi.create', compact('siswa'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]);
    Absensi::create($data);

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan');
}

public function edit(Absensi $absensi)
{
    if (!in_array($absensi->siswa->kelas, $this->kelasGuru())) {
        abort(403);
    }
    $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
    return view('absensi.edit', compact('absensi', 'siswa'));
}

public function update(Request $request, Absensi $absensi)
{
    if (!in_array($absensi->siswa->kelas, $this->kelasGuru())) {
        abort(403);
    }
    $data = $request->validate([
        'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]);
    $absensi->update($data);

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate');
}

    public function destroy(Absensi $absensi)
    {
        if (!in_array($absensi->siswa->kelas, $this->kelasGuru())) {
            abort(403);
        }
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $kelas = $request->input('kelas');

        $siswaQuery = Siswa::whereIn('kelas', $this->kelasGuru());
        if ($kelas) {
            $siswaQuery->where('kelas', $kelas);
        }

        $rekap = $siswaQuery->withCount([
            'absensi as hadir' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'Hadir')
                  ->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            },
            'absensi as izin' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'Izin')
                  ->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            },
            'absensi as sakit' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'Sakit')
                  ->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            },
            'absensi as alpha' => function ($q) use ($bulan, $tahun) {
                $q->where('status', 'Alpha')
                  ->whereMonth('tanggal', $bulan)
                  ->whereYear('tanggal', $tahun);
            },
        ])->get();

        $kelasList = $this->kelasGuru();

        return view('absensi.rekap', [
            'rekap' => $rekap,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'kelas' => $kelas,
            'kelasList' => $kelasList,
        ]);
    }

    public function exportRekap(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $kelas = $request->input('kelas');
        if ($kelas && !in_array($kelas, $this->kelasGuru())) {
            abort(403);
        }

        return Excel::download(new RekapAbsensiExport($bulan, $tahun, $kelas), 'rekap-absensi.xlsx');
    }

    public function harian(Request $request)
    {
        $kelasList = $this->kelasGuru();
        $selected = $request->input('kelas', $kelasList[0] ?? null);
        if ($selected && !in_array($selected, $kelasList)) {
            abort(403);
        }

        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $siswa = [];
        $absen = [];
        if ($selected) {
            $siswa = Siswa::where('kelas', $selected)->get();
            $absen = Absensi::whereIn('siswa_id', $siswa->pluck('id'))
                        ->where('tanggal', $tanggal)
                        ->get()
                        ->pluck('status', 'siswa_id');
        }

        return view('absensi.harian', [
            'kelasList' => $kelasList,
            'selected' => $selected,
            'tanggal' => $tanggal,
            'siswa' => $siswa,
            'absen' => $absen,
        ]);
    }

    public function harianStore(Request $request)
    {
        $kelas = $request->input('kelas');
        $tanggal = $request->input('tanggal');
        if (!$kelas || !in_array($kelas, $this->kelasGuru())) {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date',
            'status' => 'array',
        ]);

        $siswaIds = Siswa::where('kelas', $kelas)->pluck('id');
        $statusData = $request->input('status', []);
        foreach ($siswaIds as $id) {
            if (isset($statusData[$id])) {
                Absensi::updateOrCreate(
                    ['siswa_id' => $id, 'tanggal' => $tanggal],
                    ['status' => $statusData[$id]]
                );
            }
        }

        return redirect()->route('absensi.harian', ['kelas' => $kelas, 'tanggal' => $tanggal])
                         ->with('success', 'Absensi berhasil disimpan');
    }
}
