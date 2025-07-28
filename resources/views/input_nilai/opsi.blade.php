@extends('layouts.app')

@section('title', 'Pilihan Input Nilai')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<form method="GET" class="mb-3">
    <label class="me-2">Semester</label>
    <select name="semester" onchange="this.form.submit()" class="form-select d-inline w-auto">
        <option value="1" {{ $semester == 1 ? 'selected' : '' }}>1</option>
        <option value="2" {{ $semester == 2 ? 'selected' : '' }}>2</option>
    </select>
</form>
<ul class="list-group">
    <li class="list-group-item">
        <a href="{{ route('input-nilai.nilai', [$mapel->id, $kelas]) }}">Lihat Absensi</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.tugas.list', [$mapel->id, $kelas, $semester]) }}">Masukkan Nilai Tugas</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.pts.list', [$mapel->id, $kelas, $semester]) }}">Masukkan Nilai PTS</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.pat.list', [$mapel->id, $kelas, $semester]) }}">Masukkan Nilai PAT</a>
    </li>
</ul>
<a href="{{ route('input-nilai.kelas', $mapel->id) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
