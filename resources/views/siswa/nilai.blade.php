@extends('layouts.app')

@section('title', 'Nilai Saya')

@section('content')
<h1 class="mb-3">Nilai Saya</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mata Pelajaran</th>
            <th>Semester</th>
            <th>Nilai Absensi</th>
            <th>Nilai Tugas</th>
            <th>PTS</th>
            <th>PAT</th>
            <th>Nilai Raport</th>
        </tr>
    </thead>
    <tbody>
        @foreach($penilaian as $p)
        <tr>
            <td>{{ $p->mapel->nama }}</td>
            <td>{{ $p->semester }}</td>
            <td>{{ number_format($p->nilai_absensi, 2) }}</td>
            <td>{{ number_format($p->nilai_tugas, 2) }}</td>
            <td>{{ $p->pts ?? '-' }}</td>
            <td>{{ $p->pat ?? '-' }}</td>
            <td>{{ number_format($p->nilai_raport, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
