<?php

namespace App\Http\Controllers;

use App\Exports\RekapAbsensiExport;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\AbsensiSession;
use App\Models\Pengajaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    private function kelasGuru(): array
    {
        $user = Auth::user();
        if ($user && $user->role === 'admin') {
            return Kelas::pluck('nama')->toArray();
        }

        $guru = Guru::where('user_id', $user?->id)->first();
        if (! $guru) {
            return [];
        }

        return Pengajaran::where('guru_id', $guru->id)->pluck('kelas')->toArray();
    }

    public function index(Request $request)
    {
        if (Auth::user()?->role === 'guru') {
            return redirect()->route('absensi.pelajaran');
        }

        // Previously teachers were redirected to the input-per-mapel page.
        // The redirect is removed so that teachers can also view the absensi
        // index page like admins.
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

        $absensi = $query->paginate(10)->withQueryString();

        return view('absensi.index', compact('absensi', 'search'));
    }

    public function create()
    {
        $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
        $mapel = MataPelajaran::all();

        return view('absensi.create', compact('siswa', 'mapel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'check_in_at' => 'nullable|date',
            'check_out_at' => 'nullable|date',
        ]);
        Absensi::create($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan');
    }

    public function edit(Absensi $absensi)
    {
        if (! in_array($absensi->siswa->kelas, $this->kelasGuru())) {
            abort(403);
        }
        $siswa = Siswa::whereIn('kelas', $this->kelasGuru())->get();
        $mapel = MataPelajaran::all();

        return view('absensi.edit', compact('absensi', 'siswa', 'mapel'));
    }

    public function update(Request $request, Absensi $absensi)
    {
        if (! in_array($absensi->siswa->kelas, $this->kelasGuru())) {
            abort(403);
        }
        $data = $request->validate([
            'siswa_id' => ['required', Rule::exists('siswa', 'id')->whereIn('kelas', $this->kelasGuru())],
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'check_in_at' => 'nullable|date',
            'check_out_at' => 'nullable|date',
        ]);
        $absensi->update($data);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil diupdate');
    }

    public function destroy(Absensi $absensi)
    {
        if (! in_array($absensi->siswa->kelas, $this->kelasGuru())) {
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
        if ($kelas && ! in_array($kelas, $this->kelasGuru())) {
            abort(403);
        }

        return Excel::download(new RekapAbsensiExport($bulan, $tahun, $kelas), 'rekap-absensi.xlsx');
    }

    public function pelajaran(Request $request)
    {
        $hariMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
        $hari = $hariMap[date('l', strtotime($tanggal))];

        if (Auth::user()->role === 'admin') {
            $jadwalQuery = Jadwal::with(['mapel', 'kelas'])->where('hari', $hari);

            if ($request->filled('kelas_id')) {
                $jadwalQuery->where('kelas_id', $request->kelas_id);
            }

            if ($request->filled('mapel_id')) {
                $jadwalQuery->where('mapel_id', $request->mapel_id);
            }

            $jadwal = Jadwal::mergeConsecutive(
                $jadwalQuery->orderBy('jam_mulai')->get()
            );
            $kelasOptions = Kelas::all();
            $mapelOptions = MataPelajaran::all();

            return view('absensi.pelajaran', compact(
                'jadwal',
                'hari',
                'tanggal',
                'kelasOptions',
                'mapelOptions'
            ));
        }

        $guru = Guru::where('user_id', Auth::id())->first();
        if (! $guru) {
            $jadwal = collect();
        } else {
            $jadwal = Jadwal::with(['mapel', 'kelas'])
                ->where('guru_id', $guru->id)
                ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
                ->orderBy('jam_mulai')
                ->get()
                ->groupBy('hari')
                ->map(fn ($items) => Jadwal::mergeConsecutive($items));
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $startOfWeek = Carbon::now()->startOfWeek();
        $dates = [];
        foreach ($days as $index => $day) {
            $dates[$day] = $startOfWeek->copy()->addDays($index)->format('Y-m-d');
        }

        return view('absensi.pelajaran', [
            'jadwal' => $jadwal,
            'days' => $days,
            'dates' => $dates,
        ]);
    }

    public function pelajaranForm(Request $request, Jadwal $jadwal)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if ($guru && $guru->id !== $jadwal->guru_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
        $isFuture = Carbon::parse($tanggal)->isFuture();
        $kelasNama = $jadwal->kelas->nama;
        $siswa = Siswa::where('kelas', $kelasNama)->get();
        $absen = Absensi::whereIn('siswa_id', $siswa->pluck('id'))
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('tanggal', $tanggal)
            ->get()
            ->pluck('status', 'siswa_id');

        return view('absensi.pelajaran_form', [
            'jadwal' => $jadwal,
            'tanggal' => $tanggal,
            'siswa' => $siswa,
            'absen' => $absen,
            'isFuture' => $isFuture,
        ]);
    }

    public function pelajaranStore(Request $request, Jadwal $jadwal)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if ($guru && $guru->id !== $jadwal->guru_id && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'status' => 'array',
        ]);

        $tanggal = $request->input('tanggal', Carbon::now()->toDateString());
        $siswaIds = Siswa::where('kelas', $jadwal->kelas->nama)->pluck('id');
        $statusData = $request->input('status', []);
        foreach ($siswaIds as $id) {
            if (isset($statusData[$id])) {
                Absensi::updateOrCreate(
                    ['siswa_id' => $id, 'mapel_id' => $jadwal->mapel_id, 'tanggal' => $tanggal],
                    ['status' => $statusData[$id]]
                );
            }
        }

        return redirect()->route('absensi.pelajaran.form', [$jadwal->id, 'tanggal' => $tanggal])
            ->with('success', 'Absensi berhasil disimpan');
    }

    public function session(Jadwal $jadwal)
    {
        $session = AbsensiSession::where('jadwal_id', $jadwal->id)
            ->where('tanggal', Carbon::now()->toDateString())
            ->first();

        return view('absensi.session', compact('jadwal', 'session'));
    }

    public function startSession(Jadwal $jadwal)
    {
        $now = Carbon::now();
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $currentDay = $dayMap[$now->format('l')] ?? '';
        $startTime = Carbon::createFromFormat('H:i', $jadwal->jam_mulai);
        $endTime = Carbon::createFromFormat('H:i', $jadwal->jam_selesai);

        if (
            $currentDay !== $jadwal->hari ||
            $now->lt($startTime) ||
            $now->gt($endTime)
        ) {
            abort(403, 'Sesi absensi hanya bisa dibuka sesuai jadwal');
        }

        $tanggal = $now->toDateString();
        AbsensiSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => $tanggal,
            'opened_by' => Auth::id(),
            'status_sesi' => 'open',
        ]);

        $siswaIds = Siswa::where('kelas', $jadwal->kelas->nama)->pluck('id');
        foreach ($siswaIds as $id) {
            Absensi::updateOrCreate(
                ['siswa_id' => $id, 'mapel_id' => $jadwal->mapel_id, 'tanggal' => $tanggal],
                ['status' => 'Alpha']
            );
        }

        return redirect()->route('absensi.session', $jadwal->id)->with('success', 'Sesi absensi dibuka');
    }

    public function endSession(Jadwal $jadwal)
    {
        AbsensiSession::where('jadwal_id', $jadwal->id)
            ->where('tanggal', Carbon::now()->toDateString())
            ->update(['status_sesi' => 'closed']);

        return redirect()->route('absensi.session', $jadwal->id)->with('success', 'Sesi absensi ditutup');
    }
}
