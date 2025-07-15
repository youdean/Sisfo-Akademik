@extends('layouts.app')

@section('title', 'Data Diri')

@section('content')
<h1 class="mb-3">Data Diri</h1>
<table class="table table-bordered w-50">
    <tr><th>Nama</th><td>{{ $siswa->nama }}</td></tr>
    <tr><th>NISN</th><td>{{ $siswa->nisn }}</td></tr>
    <tr><th>Email</th><td>{{ $siswa->user?->email ?? '-' }}</td></tr>
    <tr><th>Kelas</th><td>{{ $siswa->kelas }}</td></tr>
    <tr><th>Tahun Ajaran</th><td>{{ $siswa->tahunAjaran?->nama }}</td></tr>
    <tr><th>Tempat Lahir</th><td>{{ $siswa->tempat_lahir }}</td></tr>
    <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin }}</td></tr>
    <tr><th>Tanggal Lahir</th><td>{{ $siswa->tanggal_lahir }}</td></tr>
</table>
@endsection
