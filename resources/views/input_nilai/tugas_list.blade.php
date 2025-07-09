@extends('layouts.app')

@section('title', 'Masukkan Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<a href="{{ route('input-nilai.tugas.form', [$mapel->id, $kelas]) }}" class="btn btn-primary mb-3">Input Nilai Tugas</a>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@forelse($tugas as $nama => $list)
<div class="mb-3">
    <div class="d-flex align-items-center">
        <h5 class="mb-0 flex-grow-1">Nama Tugas: {{ $nama }}</h5>
        <a href="{{ route('input-nilai.tugas.edit', [$mapel->id, $kelas, $nama]) }}" class="btn btn-sm btn-warning me-2">Edit</a>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tugasModal{{ $loop->iteration }}">Lihat</button>
    </div>

    <div class="modal fade" id="tugasModal{{ $loop->iteration }}" tabindex="-1" aria-labelledby="tugasModalLabel{{ $loop->iteration }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tugasModalLabel{{ $loop->iteration }}">Nama Tugas: {{ $nama }}</h5>
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
                                    <td>{{ optional($list->firstWhere('penilaian.siswa_id', $s->id))->nilai ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
    <p>Tidak ada nilai tugas.</p>
@endforelse
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
