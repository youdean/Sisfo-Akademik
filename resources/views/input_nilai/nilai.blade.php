@extends('layouts.app')

@section('title', 'Nilai Siswa')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Nilai Absensi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $s)
            <tr>
                <td>{{ $s->nama }}</td>
                <td>{{ number_format($nilaiAbsensi[$s->id] ?? 0, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<a href="{{ route('input-nilai.opsi', [$mapel->id, $kelas]) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
