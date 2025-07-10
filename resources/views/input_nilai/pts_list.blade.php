@extends('layouts.app')

@section('title', 'Nilai PTS')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<a href="{{ route('input-nilai.pts.edit', [$mapel->id, $kelas]) }}" class="btn btn-primary mb-3">Edit Nilai PTS</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>PTS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ $nilai[$s->id] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
