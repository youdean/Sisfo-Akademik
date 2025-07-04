<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Exports\RekapAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $query = Absensi::with('siswa');

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
    $siswa = Siswa::all();
    return view('absensi.create', compact('siswa'));
}

public function store(Request $request)
{
    Absensi::create($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]));

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan');
}

public function edit(Absensi $absensi)
{
    $siswa = Siswa::all();
    return view('absensi.edit', compact('absensi', 'siswa'));
}

public function update(Request $request, Absensi $absensi)
{
    $absensi->update($request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'tanggal' => 'required|date',
        'status' => 'required|in:Hadir,Izin,Sakit,Alpha'
    ]));

    return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate');
}

    public function destroy(Absensi $absensi)
    {
        $absensi->delete();
        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil dihapus');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $kelas = $request->input('kelas');

        $siswaQuery = Siswa::query();
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

        $kelasList = Siswa::select('kelas')->distinct()->pluck('kelas');

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

        return Excel::download(new RekapAbsensiExport($bulan, $tahun, $kelas), 'rekap-absensi.xlsx');
    }
}
