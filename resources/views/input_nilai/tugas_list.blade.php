@extends('layouts.app')

@section('title', 'Daftar Nilai Tugas')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@foreach($tugas as $nomor => $list)
    <h4 class="mt-4">Tugas Nomor {{ $nomor }} <a href="{{ route('input-nilai.tugas.edit', [$mapel->id, $kelas, $nomor]) }}" class="btn btn-sm btn-warning ms-2">Edit</a></h4>
    <table class="table table-bordered">
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
@endforeach
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
