@extends('layouts.app')

@section('title', 'Pilihan Input Nilai')

@section('content')
<h1 class="mb-3">{{ $mapel->nama }} - Kelas {{ $kelas }}</h1>
<ul class="list-group">
    <li class="list-group-item">
        <a href="{{ route('input-nilai.nilai', [$mapel->id, $kelas]) }}">Lihat Absensi</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.tugas.list', [$mapel->id, $kelas]) }}">Masukkan Nilai Tugas</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.pts.list', [$mapel->id, $kelas]) }}">Masukkan Nilai PTS</a>
    </li>
    <li class="list-group-item">
        <a href="{{ route('input-nilai.pat.list', [$mapel->id, $kelas]) }}">Masukkan Nilai PAT</a>
    </li>
</ul>
<a href="{{ route('input-nilai.kelas', $mapel->id) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
