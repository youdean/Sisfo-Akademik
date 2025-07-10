@extends('layouts.app')

@section('title', 'Nilai PAT')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<a href="{{ route('input-nilai.pat.edit', [$mapel->id, $kelas]) }}" class="btn btn-primary mb-3">Edit Nilai PAT</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>PAT</th>
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
