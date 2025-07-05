@extends('layouts.app')

@section('title', 'Daftar Pengajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Pengajaran</h1>
    <a href="{{ route('pengajaran.create') }}" class="btn btn-primary">+ Tambah Pengajaran</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Guru</th>
            <th>Mata Pelajaran</th>
            <th>Kelas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pengajaran as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->guru->nama }}</td>
            <td>{{ $p->mapel->nama }}</td>
            <td>{{ $p->kelas }}</td>
            <td>
                <a href="{{ route('pengajaran.edit', $p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('pengajaran.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
