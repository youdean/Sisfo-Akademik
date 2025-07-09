<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Pengajaran;
use App\Models\Siswa;
use App\Models\Penilaian;
use App\Models\NilaiTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputNilaiController extends Controller
{
    private function guru()
    {
        return Guru::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $guru = $this->guru();
        $mapelList = Pengajaran::where('guru_id', $guru->id)
            ->with('mapel')
            ->get()
            ->pluck('mapel')
            ->unique('id');
        return view('input_nilai.index', ['mapelList' => $mapelList]);
    }

    public function kelas(MataPelajaran $mapel)
    {
        $guru = $this->guru();
        $kelasList = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->pluck('kelas')
            ->unique();
        return view('input_nilai.kelas', [
            'mapel' => $mapel,
            'kelasList' => $kelasList,
        ]);
    }

    public function opsi(MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        return view('input_nilai.opsi', [
            'mapel' => $mapel,
            'kelas' => $kelas,
        ]);
    }

    public function absensi(MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $siswa = Siswa::where('kelas', $kelas)->get();
        $nilaiAbsensi = [];
        foreach ($siswa as $s) {
            $counts = Absensi::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
            $h = $counts['Hadir'] ?? 0;
            $i = $counts['Izin'] ?? 0;
            $sakit = $counts['Sakit'] ?? 0;
            $a = $counts['Alpha'] ?? 0;
            $total = $h + $i + $sakit + $a;
            $nilaiAbsensi[$s->id] = $total ? ($h / $total) * 100 : 0;
        }

        return view('input_nilai.nilai', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'siswa' => $siswa,
            'nilaiAbsensi' => $nilaiAbsensi,
        ]);
    }

    public function tugasForm(MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $siswa = Siswa::where('kelas', $kelas)->get();

        return view('input_nilai.tugas', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'siswa' => $siswa,
        ]);
    }

    public function tugasStore(Request $request, MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nilai.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('nilai', []) as $siswaId => $nilai) {
            if ($nilai === null || $nilai === '') {
                continue;
            }

            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => 1,
            ]);

            \App\Models\NilaiTugas::updateOrCreate([
                'penilaian_id' => $penilaian->id,
                'nama' => $validated['nama'],
            ], [
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('input-nilai.tugas.list', [$mapel->id, $kelas])
            ->with('success', 'Nilai tugas berhasil disimpan');
    }

    public function tugasList(MataPelajaran $mapel, $kelas)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $siswa = Siswa::where('kelas', $kelas)->get();
        $siswaIds = $siswa->pluck('id');

        $query = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $siswaIds) {
            $q->where('mapel_id', $mapel->id)
                ->whereIn('siswa_id', $siswaIds);
        })->select('nama')->distinct()->orderBy('nama');

        $names = $query->paginate(10);

        $tugas = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $siswaIds) {
            $q->where('mapel_id', $mapel->id)
                ->whereIn('siswa_id', $siswaIds);
        })->whereIn('nama', $names->items())->get()->groupBy('nama');

        return view('input_nilai.tugas_list', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'siswa' => $siswa,
            'namaTugas' => $names,
            'tugas' => $tugas,
        ]);
    }

    public function tugasEditForm(MataPelajaran $mapel, $kelas, $nama)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $siswa = Siswa::where('kelas', $kelas)->get();
        $nilai = [];
        foreach ($siswa as $s) {
            $nilaiTugas = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $s) {
                $q->where('mapel_id', $mapel->id)
                    ->where('siswa_id', $s->id);
            })->where('nama', $nama)->first();
            $nilai[$s->id] = $nilaiTugas->nilai ?? null;
        }

        return view('input_nilai.tugas_edit', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'siswa' => $siswa,
            'nama' => $nama,
            'nilai' => $nilai,
        ]);
    }

    public function tugasUpdate(Request $request, MataPelajaran $mapel, $kelas, $nama)
    {
        $guru = $this->guru();
        $exists = Pengajaran::where('guru_id', $guru->id)
            ->where('mapel_id', $mapel->id)
            ->where('kelas', $kelas)
            ->exists();
        if (!$exists) {
            abort(403);
        }

        $validated = $request->validate([
            'nilai.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('nilai', []) as $siswaId => $nilai) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => 1,
            ]);

            if ($nilai === null || $nilai === '') {
                NilaiTugas::where('penilaian_id', $penilaian->id)
                    ->where('nama', $nama)
                    ->delete();
                continue;
            }

            NilaiTugas::updateOrCreate([
                'penilaian_id' => $penilaian->id,
                'nama' => $nama,
            ], [
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('input-nilai.tugas.list', [$mapel->id, $kelas])
            ->with('success', 'Nilai tugas berhasil diperbarui');
    }
}
