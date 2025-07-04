@extends('layouts.app')

@section('title', 'Daftar Nilai Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Nilai</h1>
    <a href="{{ route('nilai.create') }}" class="btn btn-primary">+ Tambah Nilai</a>
</div>
<form method="GET" class="mb-3 d-flex" action="{{ route('nilai.index') }}">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nilai..." class="form-control me-2">
    <button class="btn btn-outline-secondary" type="submit">Search</button>
</form>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Siswa</th>
            <th>Mapel</th>
            <th>Nilai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($nilai as $n)
        <tr>
            <td>{{ $n->id }}</td>
            <td>{{ $n->siswa->nama }}</td>
            <td>{{ $n->mapel->nama }}</td>
            <td>{{ $n->nilai }}</td>
            <td>
                <a href="{{ route('nilai.edit', $n->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('nilai.destroy', $n->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
