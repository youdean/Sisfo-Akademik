@extends('layouts.app')

@section('title', 'Daftar Tahun Ajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Tahun Ajaran</h1>
    <a href="{{ route('tahun-ajaran.create') }}" class="btn btn-primary">+ Tambah Tahun Ajaran</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Mulai</th>
            <th>Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tahun_ajaran as $ta)
        <tr>
            <td>{{ $ta->id }}</td>
            <td>{{ $ta->nama }}</td>
            <td>{{ $ta->start_date }}</td>
            <td>{{ $ta->end_date }}</td>
            <td>
                <a href="{{ route('tahun-ajaran.edit', $ta->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('tahun-ajaran.destroy', $ta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $tahun_ajaran->links('pagination::bootstrap-5') }}
@endsection
