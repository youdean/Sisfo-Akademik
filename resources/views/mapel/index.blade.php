@extends('layouts.app')

@section('title', 'Daftar Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Mata Pelajaran</h1>
    <a href="{{ route('mapel.create') }}" class="btn btn-primary">+ Tambah Mapel</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Mapel</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mapel as $m)
        <tr>
            <td>{{ $m->id }}</td>
            <td>{{ $m->nama }}</td>
            <td>
                <a href="{{ route('mapel.edit', $m->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('mapel.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
