@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Siswa</h1>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary">+ Tambah Siswa</a>
</div>
<form method="GET" class="mb-3 d-flex" action="{{ route('siswa.index') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="form-control me-2">
    <button class="btn btn-outline-secondary" type="submit">Search</button>
</form>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<a href="{{ route('siswa.export') }}" class="btn btn-success mb-3">Export Semua Siswa (Excel)</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Kelas</th>
            <th>Tempat Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $s)
        <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->nama }}</td>
            <td>{{ $s->nisn }}</td>
            <td>{{ $s->kelas }}</td>
            <td>{{ $s->tempat_lahir }}</td>
            <td>{{ $s->jenis_kelamin }}</td>
            <td>{{ $s->tanggal_lahir }}</td>
            <td>
                <a href="{{ route('siswa.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $siswa->links('pagination::bootstrap-5') }}
@endsection
