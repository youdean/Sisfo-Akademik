@extends('layouts.app')

@section('title', 'Data Diri')

@section('content')
<h1 class="mb-3">Data Diri</h1>
<table class="table table-bordered w-50">
    <tr><th>Nama</th><td>{{ $siswa->nama }}</td></tr>
    <tr><th>Kelas</th><td>{{ $siswa->kelas }}</td></tr>
    <tr><th>Tanggal Lahir</th><td>{{ $siswa->tanggal_lahir }}</td></tr>
</table>
@endsection
