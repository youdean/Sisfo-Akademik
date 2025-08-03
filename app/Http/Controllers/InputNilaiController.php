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
        $kelasList = Pengajaran::where('guru_id', $guru->id)
            ->pluck('kelas')
            ->unique();
        return view('input_nilai.index', ['kelasList' => $kelasList]);
    }

    public function mapel($kelas)
    {
        $guru = $this->guru();
        $mapelList = Pengajaran::where('guru_id', $guru->id)
            ->where('kelas', $kelas)
            ->with('mapel')
            ->get()
            ->pluck('mapel')
            ->unique('id');
        return view('input_nilai.mapel', [
            'kelas' => $kelas,
            'mapelList' => $mapelList,
        ]);
    }

    public function opsi(Request $request, MataPelajaran $mapel, $kelas)
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
            'semester' => $request->query('semester', 1),
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

    public function tugasForm(MataPelajaran $mapel, $kelas, $semester)
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
            'semester' => $semester,
            'siswa' => $siswa,
        ]);
    }

    public function tugasStore(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
                'semester' => $semester,
            ]);

            \App\Models\NilaiTugas::updateOrCreate([
                'penilaian_id' => $penilaian->id,
                'nama' => $validated['nama'],
            ], [
                'nilai' => $nilai,
            ]);
        }

        return redirect()->route('input-nilai.tugas.list', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai tugas berhasil disimpan');
    }

    public function tugasList(MataPelajaran $mapel, $kelas, $semester)
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

        $query = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $siswaIds, $semester) {
            $q->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->whereIn('siswa_id', $siswaIds);
        })
            ->select('nama')
            ->groupBy('nama')
            ->orderBy('nama');

        $names = $query->paginate(10);
        $names->getCollection()->transform(fn ($item) => $item->nama);
        $nameValues = $names->items();

        $tugas = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $siswaIds, $semester) {
            $q->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->whereIn('siswa_id', $siswaIds);
        })->whereIn('nama', $nameValues)->get()->groupBy('nama');

        return view('input_nilai.tugas_list', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'namaTugas' => $names,
            'tugas' => $tugas,
        ]);
    }

    public function tugasEditForm(MataPelajaran $mapel, $kelas, $semester, $nama)
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
            $nilaiTugas = NilaiTugas::whereHas('penilaian', function ($q) use ($mapel, $s, $semester) {
                $q->where('mapel_id', $mapel->id)
                    ->where('semester', $semester)
                    ->where('siswa_id', $s->id);
            })->where('nama', $nama)->first();
            $nilai[$s->id] = $nilaiTugas->nilai ?? null;
        }

        return view('input_nilai.tugas_edit', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nama' => $nama,
            'nilai' => $nilai,
        ]);
    }

    public function tugasUpdate(Request $request, MataPelajaran $mapel, $kelas, $semester, $nama)
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
                'semester' => $semester,
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

        return redirect()->route('input-nilai.tugas.list', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai tugas berhasil diperbarui');
    }

    public function ptsPatForm(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = [
                'pts' => $penilaian->pts ?? null,
                'pat' => $penilaian->pat ?? null,
            ];
        }

        return view('input_nilai.pts_pat', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function ptsPatStore(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
            'pts.*' => 'nullable|integer|min:0|max:100',
            'pat.*' => 'nullable|integer|min:0|max:100',
        ]);

        $pts = $request->input('pts', []);
        $pat = $request->input('pat', []);

        $siswaIds = array_unique(array_merge(array_keys($pts), array_keys($pat)));
        foreach ($siswaIds as $siswaId) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => $semester,
            ]);

            $penilaian->update([
                'pts' => $pts[$siswaId] !== null && $pts[$siswaId] !== '' ? $pts[$siswaId] : null,
                'pat' => $pat[$siswaId] !== null && $pat[$siswaId] !== '' ? $pat[$siswaId] : null,
            ]);
        }

        return redirect()->route('input-nilai.pts-pat.form', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai PTS dan PAT berhasil disimpan');
    }

    public function ptsForm(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pts ?? null;
        }

        return view('input_nilai.pts', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function ptsStore(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
            'pts.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('pts', []) as $siswaId => $nilai) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => $semester,
            ]);

            $penilaian->update([
                'pts' => $nilai !== null && $nilai !== '' ? $nilai : null,
            ]);
        }

        return redirect()->route('input-nilai.pts.form', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai PTS berhasil disimpan');
    }

    public function patForm(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pat ?? null;
        }

        return view('input_nilai.pat', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function patStore(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
            'pat.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('pat', []) as $siswaId => $nilai) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => $semester,
            ]);

            $penilaian->update([
                'pat' => $nilai !== null && $nilai !== '' ? $nilai : null,
            ]);
        }

        return redirect()->route('input-nilai.pat.form', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai PAT berhasil disimpan');
    }

    public function ptsList(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pts ?? null;
        }

        return view('input_nilai.pts_list', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function ptsEditForm(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pts ?? null;
        }

        return view('input_nilai.pts_edit', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function ptsUpdate(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
            'pts.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('pts', []) as $siswaId => $nilai) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => $semester,
            ]);

            $penilaian->update([
                'pts' => $nilai !== null && $nilai !== '' ? $nilai : null,
            ]);
        }

        return redirect()->route('input-nilai.pts.list', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai PTS berhasil diperbarui');
    }

    public function patList(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pat ?? null;
        }

        return view('input_nilai.pat_list', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function patEditForm(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = $penilaian->pat ?? null;
        }

        return view('input_nilai.pat_edit', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }

    public function patUpdate(Request $request, MataPelajaran $mapel, $kelas, $semester)
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
            'pat.*' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->input('pat', []) as $siswaId => $nilai) {
            $penilaian = Penilaian::firstOrCreate([
                'siswa_id' => $siswaId,
                'mapel_id' => $mapel->id,
                'semester' => $semester,
            ]);

            $penilaian->update([
                'pat' => $nilai !== null && $nilai !== '' ? $nilai : null,
            ]);
        }

        return redirect()->route('input-nilai.pat.list', [$mapel->id, $kelas, $semester])
            ->with('success', 'Nilai PAT berhasil diperbarui');
    }

    public function ptsPatList(MataPelajaran $mapel, $kelas, $semester)
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
            $penilaian = Penilaian::where('siswa_id', $s->id)
                ->where('mapel_id', $mapel->id)
                ->where('semester', $semester)
                ->first();
            $nilai[$s->id] = [
                'pts' => $penilaian->pts ?? null,
                'pat' => $penilaian->pat ?? null,
            ];
        }

        return view('input_nilai.pts_pat_list', [
            'mapel' => $mapel,
            'kelas' => $kelas,
            'semester' => $semester,
            'siswa' => $siswa,
            'nilai' => $nilai,
        ]);
    }
}
