@extends('layouts.app')

@section('title', 'Nilai Absensi')

@section('content')
<h1 class="mb-3">Nilai Absensi</h1>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Alpha</th>
            <th>Nilai Absensi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $hadir }}</td>
            <td>{{ $izin }}</td>
            <td>{{ $sakit }}</td>
            <td>{{ $alpha }}</td>
            <td>{{ number_format($nilaiAbsensi, 2) }}</td>
        </tr>
    </tbody>
</table>
@endsection
