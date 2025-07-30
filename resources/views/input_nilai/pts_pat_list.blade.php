@extends('layouts.app')

@section('title', 'Nilai PTS & PAT')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }} Semester {{ $semester }}</h1>
<div class="mb-3">
    <a href="{{ route('input-nilai.pts.edit', [$mapel->id, $kelas, $semester]) }}" class="btn btn-sm btn-primary me-2">Edit PTS</a>
    <a href="{{ route('input-nilai.pat.edit', [$mapel->id, $kelas, $semester]) }}" class="btn btn-sm btn-primary">Edit PAT</a>
</div>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>PTS</th>
            <th>PAT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ $nilai[$s->id]['pts'] ?? '-' }}</td>
                <td>{{ $nilai[$s->id]['pat'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas, 'semester' => $semester]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
