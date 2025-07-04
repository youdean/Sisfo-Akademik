@extends('layouts.app')

@section('title', 'Daftar Tahun Ajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Tahun Ajaran</h1>
    <a href="{{ route('tahunajaran.create') }}" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tahun Ajaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tahunAjaran as $t)
        <tr>
            <td>{{ $t->id }}</td>
            <td>{{ $t->tahun }}</td>
            <td>
                <a href="{{ route('tahunajaran.edit', $t->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('tahunajaran.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
