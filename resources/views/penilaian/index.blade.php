@extends('layouts.app')

@section('title', 'Data Penilaian')

@section('content')
<h1>Data Penilaian</h1>
<form method="GET" action="{{ route('penilaian.index') }}" class="row g-2 mb-3">
    <div class="col-md-3">
        <input type="text" name="nama" value="{{ request('nama') }}" class="form-control" placeholder="Nama Siswa">
    </div>
    <div class="col-md-3">
        <input type="text" name="kelas" value="{{ request('kelas') }}" class="form-control" placeholder="Kelas">
    </div>
    <div class="col-md-3">
        <input type="text" name="mapel" value="{{ request('mapel') }}" class="form-control" placeholder="Mata Pelajaran">
    </div>
    <div class="col-md-3 d-flex">
        <button type="submit" class="btn btn-primary me-2">Cari</button>
        <a href="{{ route('penilaian.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Siswa</th>
            <th>Kelas</th>
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
            <td>{{ $p->siswa->nama }}</td>
            <td>{{ $p->siswa->kelas }}</td>
            <td>{{ $p->mapel->nama }}</td>
            <td>{{ $p->semester }}</td>
            <td>{{ number_format($p->nilai_absensi, 2) }}</td>
            <td>{{ number_format($p->nilai_tugas, 2) }}</td>
            <td>{{ $p->pts }}</td>
            <td>{{ $p->pat }}</td>
            <td>{{ number_format($p->nilai_raport, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $penilaian->links('pagination::bootstrap-5') }}
@endsection
