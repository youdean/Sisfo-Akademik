@extends('layouts.app')

@section('title', 'Daftar Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Absensi</h1>
    <div>
        <a href="{{ route('absensi.rekap') }}" class="btn btn-secondary me-2">Rekap Bulanan</a>
        <a href="{{ route('absensi.create') }}" class="btn btn-primary">+ Tambah Absensi</a>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Siswa</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($absensi as $a)
        <tr>
            <td>{{ $a->id }}</td>
            <td>{{ $a->siswa->nama }}</td>
            <td>{{ $a->tanggal }}</td>
            <td>{{ $a->status }}</td>
            <td>
                <a href="{{ route('absensi.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('absensi.destroy', $a->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
