@extends('layouts.app')

@section('title', 'Daftar Guru')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Guru</h1>
    <a href="{{ route('guru.create') }}" class="btn btn-primary">+ Tambah Guru</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($guru as $g)
        <tr>
            <td>{{ $g->id }}</td>
            <td>{{ $g->nip }}</td>
            <td>{{ $g->nama }}</td>
            <td>
                <a href="{{ route('guru.edit', $g->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('guru.destroy', $g->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
