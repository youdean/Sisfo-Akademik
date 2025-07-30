@extends('layouts.app')

@section('title', 'Daftar Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Daftar Kelas</h1>
    <a href="{{ route('kelas.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="table-responsive">
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kelas</th>
            <th>Tahun Ajaran</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kelas as $k)
        <tr>
            <td>{{ $k->id }}</td>
            <td>{{ $k->nama }}</td>
            <td>{{ $k->tahunAjaran->nama ?? '' }}</td>
            <td>{{ $k->waliKelas->nama ?? '' }}</td>
            <td>
                <a href="{{ route('kelas.edit', $k->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
{{ $kelas->links('pagination::bootstrap-5') }}
@endsection
