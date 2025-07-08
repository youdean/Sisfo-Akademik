@extends('layouts.app')

@section('title', 'Masukkan Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<a href="{{ route('input-nilai.tugas.form', [$mapel->id, $kelas]) }}" class="btn btn-primary mb-3">Input Nilai Tugas</a>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="accordion" id="tugasAccordion">
    @forelse($tugas as $nomor => $list)
    <div class="accordion-item">
        <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
            <div class="d-flex align-items-center">
                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->iteration }}">
                    Tugas Nomor {{ $nomor }}
                </button>
                <a href="{{ route('input-nilai.tugas.edit', [$mapel->id, $kelas, $nomor]) }}" class="btn btn-sm btn-warning ms-2">Edit</a>
            </div>
        </h2>
        <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#tugasAccordion">
            <div class="accordion-body p-2">
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
                                <td>
                                    {{ optional($list->firstWhere('penilaian.siswa_id', $s->id))->nilai ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
        <p>Tidak ada nilai tugas.</p>
    @endforelse
</div>
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
