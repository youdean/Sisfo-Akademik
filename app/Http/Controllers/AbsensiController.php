<?php

namespace App\Http\Controllers;

use App\Exports\RekapAbsensiExport;
use App\Models\Absensi;
use App\Models\AttendanceSession;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
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
        $hari = $request->input('hari', $hariMap[date('l', strtotime($tanggal))]);

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
            $hariOptions = array_values($hariMap);

            return view('absensi.pelajaran', compact(
                'jadwal',
                'hari',
                'tanggal',
                'kelasOptions',
                'mapelOptions',
                'hariOptions'
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

    public function openSession(Request $request, Jadwal $jadwal)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $password = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        AttendanceSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => $tanggal,
            'password' => $password,
            'opened_at' => now(),
        ]);

        return back()->with('session_password', $password);
    }

    public function closeSession(Request $request, Jadwal $jadwal)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $session = AttendanceSession::where('jadwal_id', $jadwal->id)
            ->where('tanggal', $tanggal)
            ->whereNull('closed_at')
            ->first();

        if ($session) {
            $session->update(['closed_at' => now()]);
        }

        return back()->with('success', 'Sesi ditutup');
    }

    private function mergeConsecutiveJadwal($jadwal)
    {
        $merged = collect();
        foreach ($jadwal as $item) {
            if ($merged->isNotEmpty()) {
                $last = $merged->last();
                if ($last->kelas_id === $item->kelas_id &&
                    $last->mapel_id === $item->mapel_id &&
                    $last->guru_id === $item->guru_id &&
                    $last->jam_selesai === $item->jam_mulai) {
                    $last->jam_selesai = $item->jam_selesai;

                    continue;
                }
            }
            $merged->push(clone $item);
        }

        return $merged;
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

        $activeSession = AttendanceSession::where('jadwal_id', $jadwal->id)
            ->where('tanggal', $tanggal)
            ->whereNull('closed_at')
            ->first();

        return view('absensi.pelajaran_form', [
            'jadwal' => $jadwal,
            'tanggal' => $tanggal,
            'siswa' => $siswa,
            'absen' => $absen,
            'isFuture' => $isFuture,
            'activeSession' => $activeSession,
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
}
