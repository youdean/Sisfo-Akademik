@extends('layouts.app')

@section('title', 'Masukkan Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }} Semester {{ $semester }}</h1>
<a href="{{ route('input-nilai.tugas.form', [$mapel->id, $kelas, $semester]) }}" class="btn btn-primary mb-3">Input Nilai Tugas</a>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($namaTugas->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Tugas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($namaTugas as $index => $nama)
                <tr>
                    <td>{{ $nama }}</td>
                    <td>
                        <a href="{{ route('input-nilai.tugas.edit', [$mapel->id, $kelas, $semester, $nama]) }}" class="btn btn-sm btn-warning me-2">Edit</a>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tugasModal{{ $namaTugas->firstItem() + $index }}">Lihat</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @foreach($namaTugas as $index => $nama)
        <div class="modal fade" id="tugasModal{{ $namaTugas->firstItem() + $index }}" tabindex="-1" aria-labelledby="tugasModalLabel{{ $namaTugas->firstItem() + $index }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tugasModalLabel{{ $namaTugas->firstItem() + $index }}">Nama Tugas: {{ $nama }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Siswa</th>
                                    <th>Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $s)
                                    <tr>
                                        <td>{{ $s->nama }}</td>
                                        <td>{{ optional($tugas[$nama]->firstWhere('penilaian.siswa_id', $s->id))->nilai ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $namaTugas->links() }}
@else
    <p>Tidak ada nilai tugas.</p>
@endif

<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas, 'semester' => $semester]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
